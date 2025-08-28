import pandas as pd
import numpy as np
import tensorflow as tf
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense, LSTM
from sklearn.preprocessing import MinMaxScaler
import json
import sys
import os

def create_dataset(data, look_back=1):
    """
    Creates a dataset for time series forecasting.
    """
    X, Y = [], []
    for i in range(len(data) - look_back - 1):
        a = data[i:(i + look_back), 0]
        X.append(a)
        Y.append(data[i + look_back, 0])
    return np.array(X), np.array(Y)

def main(start_date_str, end_date_str):
    """
    Main function to load data, train model, and generate forecast.
    """
    # Define the path to the historical data file
    file_path = os.path.join(os.path.dirname(__file__), 'data', 'historical_bookings.json')

    # Check if the data file exists
    if not os.path.exists(file_path):
        print(json.dumps({'error': 'Historical data file not found.'}))
        return
    
    try:
        with open(file_path, 'r') as f:
            raw_bookings = json.load(f)
    except Exception as e:
        print(json.dumps({'error': f'Failed to read data file: {str(e)}'}))
        return

    if not raw_bookings:
        print(json.dumps({'forecast_date': [], 'predicted_bookings': []}))
        return

    # --- Data Preprocessing ---
    # Convert raw booking data into a DataFrame with daily counts
    bookings_df = pd.DataFrame(raw_bookings)
    
    # KINUKUHA NA NGAYON ANG MGA PETSA MULA SA 'start_time' INSTEAD OF 'created_at'
    bookings_df['start_time'] = pd.to_datetime(bookings_df['start_time'])
    bookings_df['date'] = bookings_df['start_time'].dt.date
    
    daily_bookings = bookings_df.groupby('date').size().reset_index(name='bookings_count')
    daily_bookings['date'] = pd.to_datetime(daily_bookings['date'])
    daily_bookings = daily_bookings.set_index('date')

    # Ensure all dates are included by re-indexing
    all_dates = pd.date_range(start=daily_bookings.index.min(), end=daily_bookings.index.max(), freq='D')
    daily_bookings = daily_bookings.reindex(all_dates, fill_value=0)
    
    # --- Model Training ---
    # Use MinMaxScaler to normalize the data (important for LSTMs)
    scaler = MinMaxScaler(feature_range=(0, 1))
    scaled_data = scaler.fit_transform(daily_bookings['bookings_count'].values.reshape(-1, 1))

    # Create the dataset for the LSTM model
    look_back = 7  # Use the last 7 days of data to predict the next day

    # Check if there are enough data points to train the model
    if len(scaled_data) < look_back + 1:
        print(json.dumps({'error': 'Not enough data to train the model. At least 8 days of approved bookings are required.'}))
        return

    X, Y = create_dataset(scaled_data, look_back)
    X = np.reshape(X, (X.shape[0], X.shape[1], 1))

    # Build the LSTM model
    model = Sequential()
    model.add(LSTM(50, return_sequences=True, input_shape=(look_back, 1)))
    model.add(LSTM(50))
    model.add(Dense(1))
    model.compile(loss='mean_squared_error', optimizer='adam')

    # Train the model
    model.fit(X, Y, epochs=100, batch_size=1, verbose=0)

    # --- Forecasting ---
    # Get the last sequence of data to start forecasting
    last_sequence = scaled_data[-look_back:].reshape(1, look_back, 1)
    
    forecast_dates = pd.to_datetime(pd.date_range(start=start_date_str, end=end_date_str, freq='D'))
    forecasts = []
    
    # Predict step-by-step
    for _ in range(len(forecast_dates)):
        predicted_value = model.predict(last_sequence, verbose=0)
        forecasts.append(scaler.inverse_transform(predicted_value)[0][0])
        
        # Update the last sequence for the next prediction
        last_sequence = np.append(last_sequence[:, 1:, :], predicted_value.reshape(1, 1, 1), axis=1)

    # Convert to JSON and print the output
    response = {
        'forecast_date': forecast_dates.strftime('%Y-%m-%d').tolist(),
        'predicted_bookings': [int(round(b)) for b in forecasts]
    }
    
    print(json.dumps(response))

if __name__ == "__main__":
    if len(sys.argv) > 2:
        start_date = sys.argv[1]
        end_date = sys.argv[2]
        main(start_date, end_date)
    else:
        print(json.dumps({'error': 'Please provide start and end dates as arguments.'}))