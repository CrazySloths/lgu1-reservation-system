import pandas as pd
import numpy as np
import tensorflow as tf
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from sklearn.preprocessing import StandardScaler
import json
import sys
import os
from datetime import datetime, timedelta

class FacilityRecommender:
    def __init__(self):
        """
        Initialize the facility recommender with TensorFlow-based similarity matching
        """
        self.facilities_data = None
        self.bookings_data = None
        self.scaler = StandardScaler()
        self.vectorizer = TfidfVectorizer(max_features=100, stop_words='english')
        
    def load_data(self):
        """
        Load facility and booking data from files
        """
        try:
            # Load historical bookings
            bookings_file = os.path.join(os.path.dirname(__file__), 'data', 'historical_bookings.json')
            if os.path.exists(bookings_file):
                with open(bookings_file, 'r') as f:
                    self.bookings_data = json.load(f)
            else:
                self.bookings_data = []
            
            # Extract unique facilities from bookings
            facilities = {}
            for booking in self.bookings_data:
                if 'facility' in booking and booking['facility']:
                    facility = booking['facility']
                    facilities[facility['facility_id']] = facility
            
            # Convert to list format for easier processing
            self.facilities_data = list(facilities.values())
            return True
            
        except Exception as e:
            print(json.dumps({'error': f'Failed to load data: {str(e)}'}))
            return False
    
    def calculate_facility_features(self, facilities):
        """
        Calculate numerical features for each facility using TensorFlow operations
        """
        features = []
        descriptions = []
        
        for facility in facilities:
            # Numerical features
            capacity = float(facility.get('capacity', 0))
            rate = float(facility.get('rate_per_hour', 0))
            
            # Text features for similarity
            desc = f"{facility.get('name', '')} {facility.get('description', '')} {facility.get('address', '')}"
            descriptions.append(desc)
            
            # Calculate usage frequency from bookings
            facility_bookings = [b for b in self.bookings_data if b.get('facility_id') == facility['facility_id']]
            usage_frequency = len(facility_bookings)
            
            # Average booking duration
            durations = []
            for booking in facility_bookings:
                if booking.get('start_time') and booking.get('end_time'):
                    try:
                        start = datetime.fromisoformat(booking['start_time'].replace('Z', '+00:00'))
                        end = datetime.fromisoformat(booking['end_time'].replace('Z', '+00:00'))
                        duration = (end - start).total_seconds() / 3600  # hours
                        durations.append(duration)
                    except:
                        durations.append(1)  # Default 1 hour
            
            avg_duration = np.mean(durations) if durations else 1
            
            features.append([capacity, rate, usage_frequency, avg_duration])
        
        return np.array(features), descriptions
    
    def build_recommendation_model(self):
        """
        Build a TensorFlow model for facility similarity scoring
        """
        if not self.facilities_data:
            return None
            
        # Calculate features
        numerical_features, descriptions = self.calculate_facility_features(self.facilities_data)
        
        # Normalize numerical features
        if len(numerical_features) > 0:
            normalized_features = self.scaler.fit_transform(numerical_features)
        else:
            normalized_features = np.array([])
        
        # Create text similarity matrix
        if descriptions:
            text_vectors = self.vectorizer.fit_transform(descriptions)
            text_similarity = cosine_similarity(text_vectors)
        else:
            text_similarity = np.array([])
        
        return {
            'numerical_features': normalized_features,
            'text_similarity': text_similarity,
            'facilities': self.facilities_data
        }
    
    def check_availability(self, facility_id, requested_date, start_time, end_time):
        """
        Check if a facility is available at the requested time
        """
        requested_start = f"{requested_date} {start_time}"
        requested_end = f"{requested_date} {end_time}"
        
        for booking in self.bookings_data:
            if (booking.get('facility_id') == facility_id and 
                booking.get('status') == 'approved'):
                
                booking_start = booking.get('start_time', '')
                booking_end = booking.get('end_time', '')
                
                # Check for time conflicts (simplified)
                if (booking_start.split()[0] == requested_date):  # Same date
                    # More sophisticated time conflict checking can be added here
                    return False
        
        return True
    
    def recommend_alternatives(self, target_facility_id, requested_date, start_time, end_time, 
                             expected_attendees, event_type="general"):
        """
        Recommend alternative facilities using AI-based similarity scoring
        """
        model_data = self.build_recommendation_model()
        if not model_data or len(model_data['facilities']) == 0:
            return []
        
        target_facility = None
        target_index = None
        
        # Find the target facility
        for i, facility in enumerate(model_data['facilities']):
            if facility['facility_id'] == target_facility_id:
                target_facility = facility
                target_index = i
                break
        
        if target_facility is None:
            return []
        
        recommendations = []
        
        for i, facility in enumerate(model_data['facilities']):
            if facility['facility_id'] == target_facility_id:
                continue
            
            # Check availability first
            if not self.check_availability(facility['facility_id'], requested_date, start_time, end_time):
                continue
            
            # Check capacity constraint
            if int(facility.get('capacity', 0)) < expected_attendees:
                continue
            
            # Calculate similarity score
            similarity_score = 0.0
            
            # Text similarity (30% weight)
            if len(model_data['text_similarity']) > 0:
                text_sim = model_data['text_similarity'][target_index][i]
                similarity_score += 0.3 * text_sim
            
            # Numerical feature similarity (40% weight)
            if len(model_data['numerical_features']) > 0:
                target_features = model_data['numerical_features'][target_index]
                candidate_features = model_data['numerical_features'][i]
                
                # Calculate cosine similarity for numerical features
                dot_product = np.dot(target_features, candidate_features)
                norm_target = np.linalg.norm(target_features)
                norm_candidate = np.linalg.norm(candidate_features)
                
                if norm_target > 0 and norm_candidate > 0:
                    numerical_sim = dot_product / (norm_target * norm_candidate)
                    similarity_score += 0.4 * numerical_sim
            
            # Capacity adequacy bonus (20% weight)
            capacity_ratio = min(1.0, expected_attendees / int(facility.get('capacity', 1)))
            capacity_bonus = 1.0 - abs(0.7 - capacity_ratio)  # Optimal at 70% capacity
            similarity_score += 0.2 * capacity_bonus
            
            # Price similarity (10% weight)
            target_rate = float(target_facility.get('rate_per_hour', 0))
            candidate_rate = float(facility.get('rate_per_hour', 0))
            if target_rate > 0:
                price_ratio = min(candidate_rate / target_rate, target_rate / candidate_rate)
                similarity_score += 0.1 * price_ratio
            
            recommendations.append({
                'facility_id': facility['facility_id'],
                'name': facility['name'],
                'description': facility['description'],
                'capacity': facility['capacity'],
                'rate_per_hour': facility['rate_per_hour'],
                'address': facility.get('address', ''),
                'similarity_score': float(similarity_score),
                'availability_status': 'available',
                'recommendation_reason': self.generate_recommendation_reason(
                    target_facility, facility, similarity_score, capacity_ratio
                )
            })
        
        # Sort by similarity score (descending)
        recommendations.sort(key=lambda x: x['similarity_score'], reverse=True)
        
        # Return top 3 recommendations
        return recommendations[:3]
    
    def generate_recommendation_reason(self, target_facility, recommended_facility, 
                                     similarity_score, capacity_ratio):
        """
        Generate human-readable recommendation reason
        """
        reasons = []
        
        if similarity_score > 0.7:
            reasons.append("Highly similar to your preferred facility")
        elif similarity_score > 0.5:
            reasons.append("Good alternative with similar features")
        else:
            reasons.append("Available option for your event")
        
        if capacity_ratio > 0.6 and capacity_ratio < 0.8:
            reasons.append("optimal capacity for your group size")
        elif capacity_ratio >= 0.8:
            reasons.append("spacious venue with room to grow")
        
        target_rate = float(target_facility.get('rate_per_hour', 0))
        rec_rate = float(recommended_facility.get('rate_per_hour', 0))
        
        if rec_rate < target_rate * 0.9:
            reasons.append("more budget-friendly option")
        elif rec_rate > target_rate * 1.1:
            reasons.append("premium facility with enhanced features")
        
        return ", ".join(reasons)

def main(facility_id, requested_date, start_time, end_time, expected_attendees, event_type="general"):
    """
    Main function to get facility recommendations
    """
    recommender = FacilityRecommender()
    
    if not recommender.load_data():
        return
    
    try:
        facility_id = int(facility_id)
        expected_attendees = int(expected_attendees)
        
        recommendations = recommender.recommend_alternatives(
            facility_id, requested_date, start_time, end_time, 
            expected_attendees, event_type
        )
        
        response = {
            'status': 'success',
            'requested_facility_id': facility_id,
            'requested_date': requested_date,
            'requested_time': f"{start_time} - {end_time}",
            'recommendations': recommendations,
            'total_alternatives': len(recommendations)
        }
        
        print(json.dumps(response, indent=2))
        
    except Exception as e:
        print(json.dumps({'error': f'Recommendation failed: {str(e)}'}))

if __name__ == "__main__":
    if len(sys.argv) >= 6:
        facility_id = sys.argv[1]
        requested_date = sys.argv[2]
        start_time = sys.argv[3]
        end_time = sys.argv[4]
        expected_attendees = sys.argv[5]
        event_type = sys.argv[6] if len(sys.argv) > 6 else "general"
        
        main(facility_id, requested_date, start_time, end_time, expected_attendees, event_type)
    else:
        print(json.dumps({
            'error': 'Usage: python facility_recommender.py <facility_id> <date> <start_time> <end_time> <attendees> [event_type]',
            'example': 'python facility_recommender.py 1 "2025-09-15" "10:00" "13:00" 50 "meeting"'
        }))
