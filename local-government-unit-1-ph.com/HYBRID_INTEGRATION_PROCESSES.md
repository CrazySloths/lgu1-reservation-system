# 🔗 HYBRID INTEGRATION PROCESSES - PUBLIC FACILITIES RESERVATION SYSTEM

**Document Type:** Hybrid Process Workflows (Internal + External)  
**Date Created:** December 6, 2025  
**Purpose:** Document the 6 processes combining internal operations with external LGU system integrations

---

## 📋 TABLE OF CONTENTS

1. [Overview](#overview)
2. [Process 1: New Facility Construction](#process-1-new-facility-construction)
3. [Process 2: Government Event - Energy Efficiency](#process-2-government-event---energy-efficiency)
4. [Process 3: Government Event - Housing & Resettlement](#process-3-government-event---housing--resettlement)
5. [Process 4: Traffic Coordination](#process-4-traffic-coordination)
6. [Process 5: Damage & Maintenance](#process-5-damage--maintenance)
7. [Process 6: Payment Verification](#process-6-payment-verification)
8. [Integration Summary](#integration-summary)

---

## 📊 OVERVIEW

### **The 6 Hybrid Processes**

```
┌─────────────────────────────────────────────────────┐
│     HYBRID PROCESSES (Internal + External)          │
├─────────────────────────────────────────────────────┤
│                                                     │
│ 1. New Facility Construction................16 steps│
│    Integrates: Urban Planning + Infrastructure +    │
│                Utility Billing (3 systems)          │
│                                                     │
│ 2. Government Event (Energy)...............16 steps │
│    Integrates: Energy Efficiency System             │
│                                                     │
│ 3. Government Event (Housing)..............14 steps │
│    Integrates: Housing & Resettlement System        │
│                                                     │
│ 4. Traffic Coordination....................15 steps │
│    Integrates: Road Transportation System           │
│                                                     │
│ 5. Damage & Maintenance....................17 steps │
│    Integrates: Community Infrastructure System      │
│                                                     │
│ 6. Payment Verification....................15 steps │
│    Integrates: Treasurer's Office                   │
│                                                     │
├─────────────────────────────────────────────────────┤
│ TOTAL: 93 workflow steps across 6 processes        │
│ EXTERNAL SYSTEMS: 8 different LGU integrations      │
└─────────────────────────────────────────────────────┘
```

---

## 🏗️ PROCESS 1: NEW FACILITY CONSTRUCTION

**Integrates With:** Urban Planning + Infrastructure + Utility Billing  
**Duration:** 6-18 months  
**Trigger:** Admin identifies need for new facility

### **Process Flow**

```
INTERNAL PHASE 1: Need Identification (Steps 1-3)
├─ Admin analyzes AI insights (high demand detected)
├─ Admin initiates "Request New Facility"
└─ System prepares facility requirements

        ↓ API CALL ↓

EXTERNAL PHASE 1: Urban Planning (Steps 4-7)
├─ POST /api/land/search (send requirements)
├─ WEBHOOK receives available land parcels
├─ Admin selects land
└─ POST /api/land/reserve (confirm selection)

        ↓ API CALL ↓

EXTERNAL PHASE 2: Infrastructure (Steps 8-10)
├─ POST /api/infrastructure/projects (create project)
├─ WEBHOOK receives project_id and status updates
└─ Track construction progress

        ↓ API CALL ↓

EXTERNAL PHASE 3: Utility Billing (Steps 11-12)
├─ POST /api/utility/water-connection (request)
└─ WEBHOOK receives meter_number and account_number

        ↓ BACK TO INTERNAL ↓

INTERNAL PHASE 2: Completion (Steps 13-16)
├─ Update facility database (create new facility record)
├─ Mark facility as "Active"
├─ Add to public facility directory
└─ Seed equipment inventory
```

### **Key Integration Points**

**1. Urban Planning Integration:**
```json
// Request
POST /api/external/urban-planning/land/search
{
  "required_area": 500,
  "zoning_type": "public_facility",
  "accessibility_needs": "near_main_road",
  "min_distance_from_residential": 100
}

// Response (Webhook)
{
  "lands": [
    {
      "land_id": "LP-2025-001",
      "location": "Barangay 123",
      "area": 520,
      "zoning": "public_facility",
      "ownership": "city_owned"
    }
  ]
}
```

**2. Infrastructure Integration:**
```json
// Request
POST /api/external/infrastructure/projects
{
  "facility_type": "covered_court",
  "land_id": "LP-2025-001",
  "budget": 5000000,
  "timeline_months": 12,
  "specifications": {...}
}

// Response
{
  "project_id": "INFRA-2025-045",
  "status": "approved",
  "estimated_completion": "2026-12-31"
}
```

**3. Utility Billing Integration:**
```json
// Request
POST /api/external/utility/water-connection
{
  "project_id": "INFRA-2025-045",
  "fixture_count": {
    "toilets": 4,
    "sinks": 6,
    "drinking_fountains": 2
  }
}

// Response (Webhook)
{
  "connection_status": "approved",
  "meter_number": "WM-2025-789",
  "account_number": "UTIL-2025-456"
}
```

### **Database Changes**

**New Tables:**
- `construction_projects` (tracks facility construction)
- `external_land_selections` (land from Urban Planning)
- `utility_connections` (water meter info)

**Facility Record Created:**
```sql
INSERT INTO facilities (
  name, lgu_city_id, capacity, base_rate, status,
  water_meter_number, water_account_number,
  construction_project_id, activated_at
) VALUES (...);
```

---

## 🌿 PROCESS 2: GOVERNMENT EVENT - ENERGY EFFICIENCY

**Integrates With:** Energy Efficiency & Conservation Management System  
**Duration:** 2-4 weeks  
**Trigger:** Energy Office plans DOE seminar

### **Process Flow**

```
EXTERNAL PHASE 1: Request (Steps 1)
└─ POST /api/public-facilities/request (from Energy system)

INTERNAL PHASE 1: Admin Processing (Steps 2-9)
├─ Admin receives request in dashboard
├─ Check facility availability
├─ Check equipment availability
├─ Coordinate with organizer
├─ Get supplier quotations (for consumables)
├─ Finance approves budget
├─ Admin assigns facility (fee waived)
└─ Finalize event details

EXTERNAL PHASE 2: Confirmation (Steps 10-11)
├─ PUT /api/energy/events/{id}/schedule (send approval)
└─ WEBHOOK receives final attendee count

INTERNAL PHASE 2: Event Execution (Steps 12-14)
├─ Event happens (seminar conducted)
├─ Post-event inspection
└─ Collect feedback (QR code + paper forms)

EXTERNAL PHASE 3: Liquidation (Steps 15-16)
└─ POST /api/energy/events/{id}/liquidation (itemized report)
```

### **Key Data Exchange**

**Request Format:**
```json
{
  "event_type": "doe_energy_seminar",
  "expected_attendees": 200,
  "preferred_dates": ["2025-12-15", "2025-12-16"],
  "barangay_target": "All Barangays",
  "organizer_contact": {...}
}
```

**Response Format:**
```json
{
  "facility_assigned": "City Hall Main Hall",
  "date": "2025-12-15",
  "time": "08:00-17:00",
  "equipment_provided": ["chairs", "tables", "sound_system"],
  "facility_fee": 0,
  "equipment_fee": 15300
}
```

**Liquidation Report:**
```json
{
  "total_budget": 50000,
  "expenses": [
    {"item": "Snacks (200 pax)", "amount": 20000, "receipt": "..."},
    {"item": "Lunch (200 pax)", "amount": 25000, "receipt": "..."},
    {"item": "Handouts", "amount": 5000, "receipt": "..."}
  ],
  "actual_attendees": 185,
  "balance": 0
}
```

### **Special Features**
- ✅ Facility fee waived (government event)
- ✅ Equipment fee charged (actual cost)
- ✅ Full transparency with receipts
- ✅ Post-event feedback collection
- ✅ No raffle, no SMS follow-up (simplified)

---

## 🏘️ PROCESS 3: GOVERNMENT EVENT - HOUSING & RESETTLEMENT

**Integrates With:** Housing & Resettlement System  
**Duration:** 2-4 weeks  
**Trigger:** Housing Office plans community briefing

### **Process Flow**

```
EXTERNAL PHASE 1: Request (Step 1)
└─ POST /api/public-facilities/request (from Housing system)

INTERNAL PHASE: Processing (Steps 2-7)
├─ Admin receives request
├─ Check facility capacity (beneficiaries = attendees)
├─ Assign equipment
├─ Finance approves budget
└─ Create government booking

EXTERNAL PHASE 2: Confirmation (Steps 8-9)
├─ PUT /api/housing/events/{id}/schedule
└─ WEBHOOK confirms beneficiaries count

INTERNAL PHASE: Execution (Steps 10-12)
├─ Event happens (relocation briefing)
├─ Post-event inspection
└─ Collect feedback (optional)

EXTERNAL PHASE 3: Liquidation (Steps 13-14)
└─ POST /api/housing/events/{id}/liquidation
```

### **Key Clarification**

**Beneficiaries = Attendees (Single Field):**
```json
{
  "event_type": "relocation_briefing",
  "beneficiaries_count": 150,  // Same as expected_attendees
  "barangay_target": "Barangay 52"
}
```

**Why Single Field:**
- Beneficiaries ARE the attendees
- No need for separate tracking
- Simplifies the system

---

## 🚦 PROCESS 4: TRAFFIC COORDINATION

**Integrates With:** Road & Transportation Infrastructure Monitoring  
**Duration:** 1-2 weeks  
**Trigger:** Large event (>200 attendees) approved

### **Process Flow**

```
INTERNAL PHASE 1: Booking Approval (Steps 1-5)
├─ Citizen submits booking (500 attendees)
├─ Staff verifies
├─ Admin APPROVES booking FIRST ✓
├─ Admin evaluates traffic coordination need
└─ Use simple checklist (not prediction calculator)

EXTERNAL PHASE 1: Assessment (Steps 6-7)
├─ POST /api/traffic/assessment (request)
└─ WEBHOOK receives recommendations

INTERNAL PHASE 2: Coordination (Steps 8-11)
├─ Admin reviews recommendations
├─ Communicates to organizer
├─ Organizer acknowledges
└─ Admin finalizes coordination

EXTERNAL PHASE 2: Enforcer Dispatch (Steps 12-13)
├─ POST /api/traffic/event-schedule
└─ WEBHOOK receives enforcer assignments

INTERNAL PHASE 3: Event Day (Steps 14-15)
├─ Event with traffic management
└─ Mark as complete
```

### **Important Principles**

**✅ Booking Approved BEFORE Traffic Assessment:**
```
Admin approves booking → Status: payment_pending
     ↓
THEN (optional): Request traffic assessment
     ↓
Assessment is for COORDINATION, not approval gate
```

**Simple Checklist (Not Calculator):**
```
Traffic Coordination Checklist:
☐ Expected attendees > 200?
☐ Weekend or holiday?
☐ Near main road?
☐ Limited parking?
☐ Multiple barangays attending?

If 3+ checked → Consider requesting traffic assessment
```

**Traffic Enforcers = Free Government Service**

---

## 🔧 PROCESS 5: DAMAGE & MAINTENANCE

**Integrates With:** Community Infrastructure Maintenance Management  
**Duration:** 1-4 weeks  
**Trigger:** Post-event inspection detects damage

### **Process Flow**

```
INTERNAL PHASE 1: Inspection (Steps 1-5)
├─ Staff conducts facility inspection
├─ Detects damage (broken chairs, damaged walls)
├─ Determines responsibility (citizen/vendor)
├─ Creates damage report
└─ Admin reviews

EXTERNAL PHASE 1: Maintenance Request (Steps 6-7)
├─ POST /api/maintenance/request
└─ WEBHOOK receives repair cost + specifications

INTERNAL PHASE 2: Billing (Steps 8-12)
├─ Admin creates billing notice (2 options)
├─ Send to citizen
├─ Citizen chooses: Pay OR Replace with exact match
├─ Process payment/replacement
└─ Verify replacement items

EXTERNAL PHASE 2: Repair (Steps 13-14)
├─ PUT /api/maintenance/request/{id}/approve
└─ WEBHOOK receives repair schedule/completion

INTERNAL PHASE 3: Completion (Steps 15-17)
├─ Update facility status (operational)
├─ Log maintenance history
└─ Re-open for bookings
```

### **2-Option System for Damage**

**Option 1: Pay for Repair**
```
Broken: 5 Monobloc Chairs
Repair Cost: ₱500 (assessed by Maintenance system)
Citizen pays: ₱500
```

**Option 2: Replace with Exact Match**
```
Broken: 5 Monobloc Chairs
Specifications:
  - Type: Monobloc Chair (White)
  - Quantity: 5
  - Quality: Same as original
  - Brand: Not required, but must match specs

Citizen provides replacement:
Staff inspects → Verifies exact match → Accepts
```

### **Integration Data**

```json
// Maintenance Request
POST /api/external/maintenance/request
{
  "facility_id": 3,
  "damage_type": "equipment_broken",
  "items_damaged": [
    {"item": "Monobloc Chair", "quantity": 5}
  ],
  "urgency": "medium",
  "photos": ["..."]
}

// Response
{
  "request_id": "MAINT-2025-089",
  "repair_cost": 500,
  "estimated_days": 7,
  "item_specifications": {
    "type": "Monobloc Chair (White)",
    "quantity": 5,
    "quality_standard": "same_as_original"
  }
}
```

---

## 💳 PROCESS 6: PAYMENT VERIFICATION

**Integrates With:** Treasurer's Office  
**Duration:** Minutes to hours  
**Trigger:** Citizen completes payment

### **Process Flow**

```
INTERNAL PHASE 1: Payment Initiation (Steps 1-4)
├─ Citizen selects payment method (GCash/Cash/Bank)
├─ Process via payment gateway
├─ Receive confirmation from gateway
└─ Store payment details

EXTERNAL PHASE: Treasurer Verification (Steps 5-8)
├─ POST /api/treasurer/payments (send payment data)
├─ Treasurer validates payment
├─ Treasurer issues OR number
└─ WEBHOOK /webhook/treasurer/confirm (receive OR)

INTERNAL PHASE 2: Confirmation (Steps 9-15)
├─ Update payment status
├─ Update booking status (confirmed)
├─ Generate booking confirmation document
├─ Generate QR code for event entry
├─ Send confirmation email (with OR + QR)
├─ Send SMS notification
└─ Update admin dashboard
```

### **Integration Data**

**Payment Submission:**
```json
POST /api/external/treasurer/payments
{
  "booking_id": 12345,
  "amount": 6300,
  "gateway": "gcash",
  "gateway_transaction_id": "GC-2025-789456",
  "payment_date": "2025-12-05T10:30:00+08:00",
  "payer_name": "Maria Santos",
  "payer_contact": "0917-123-4567"
}
```

**Treasurer Webhook:**
```json
POST /webhook/treasurer/confirm
{
  "booking_id": 12345,
  "status": "confirmed",
  "or_number": "TRS-2025-00123",
  "cashier_name": "Juan Dela Cruz",
  "cashier_id": "TREAS-456",
  "confirmed_at": "2025-12-05T10:45:00+08:00"
}
```

**Email Sent to Citizen:**
```
Subject: Booking Confirmed! OR #TRS-2025-00123

✅ Your booking is CONFIRMED!

Booking ID: 12345
Facility: Covered Court
Date: December 14, 2025
Time: 2:00 PM - 5:00 PM
Amount Paid: ₱6,300
OR Number: TRS-2025-00123

Attachments:
- Booking Confirmation.pdf
- Official Receipt.pdf
- QR Code for Entry.png
```

---

## 📊 INTEGRATION SUMMARY

### **Complete System Architecture**

```
┌─────────────────────────────────────────────────────┐
│   PUBLIC FACILITIES RESERVATION SYSTEM (CENTER)      │
└─────────────────────────────────────────────────────┘
                        ↕
    ┌───────────────────┼───────────────────┐
    ↓                   ↓                   ↓
┌─────────┐      ┌─────────┐        ┌─────────┐
│ Urban   │      │Infrastr-│        │ Utility │
│Planning │      │ucture   │        │ Billing │
└─────────┘      └─────────┘        └─────────┘
                                            ↓
    ┌───────────────────┼───────────────────┐
    ↓                   ↓                   ↓
┌─────────┐      ┌─────────┐        ┌─────────┐
│ Energy  │      │ Housing │        │  Road   │
│Efficien-│      │& Resetl-│        │Transport│
│cy       │      │ement    │        │         │
└─────────┘      └─────────┘        └─────────┘
                                            ↓
    ┌───────────────────┼───────────────────┐
    ↓                   ↓                   
┌─────────┐      ┌─────────┐        
│Maintena-│      │Treasur- │        
│nce      │      │er Office│        
└─────────┘      └─────────┘        
```

### **Integration Statistics**

| Process | Internal Steps | External Steps | External Systems | Duration |
|---------|---------------|----------------|------------------|----------|
| New Facility Construction | 6 | 10 | 3 (Urban + Infra + Utility) | 6-18 months |
| Energy Efficiency Event | 10 | 6 | 1 (Energy) | 2-4 weeks |
| Housing Event | 9 | 5 | 1 (Housing) | 2-4 weeks |
| Traffic Coordination | 9 | 6 | 1 (Road Transport) | 1-2 weeks |
| Damage & Maintenance | 11 | 6 | 1 (Maintenance) | 1-4 weeks |
| Payment Verification | 10 | 5 | 1 (Treasurer) | Minutes |
| **TOTAL** | **55** | **38** | **8 unique systems** | **Varies** |

### **API Endpoints Summary**

**Outgoing (Public Facilities → External):**
- POST /api/external/urban-planning/land/search
- POST /api/external/infrastructure/projects
- POST /api/external/utility/water-connection
- POST /api/external/energy/events/{id}/schedule
- POST /api/external/housing/events/{id}/schedule
- POST /api/external/traffic/assessment
- POST /api/external/maintenance/request
- POST /api/external/treasurer/payments

**Incoming (External → Public Facilities):**
- POST /webhook/land/available
- POST /webhook/infrastructure/status
- POST /webhook/utility/connection-status
- POST /webhook/energy/confirmed
- POST /webhook/housing/confirmed
- POST /webhook/traffic/assessment
- POST /webhook/maintenance/completed
- POST /webhook/treasurer/confirm

### **Database Tables for External Integration**

```sql
-- External integration tracking
external_api_logs (tracks all API calls)
external_webhooks (logs all webhook receipts)

-- Per-system tables
land_selections (from Urban Planning)
construction_projects (Infrastructure tracking)
utility_connections (water meter info)
government_event_requests (Energy + Housing)
traffic_assessments (Road Transport coordination)
maintenance_requests (repairs and replacements)
treasurer_payments (OR numbers and verification)
```

---

## ✅ HYBRID PROCESS COMPLETE

### **System Capabilities**

**Internal Operations (5 Processes):**
1. ✅ Complete booking workflow
2. ✅ Two-tier discount calculation
3. ✅ Equipment rental with real-time inventory
4. ✅ Schedule conflict detection
5. ✅ AI-powered analytics

**Hybrid Operations (6 Processes):**
1. ✅ New facility construction (3 systems)
2. ✅ Government events - Energy Efficiency
3. ✅ Government events - Housing & Resettlement
4. ✅ Traffic coordination for large events
5. ✅ Damage repair and maintenance
6. ✅ Payment verification with OR issuance

**Total Integration:**
- **11 major processes**
- **8 external LGU systems**
- **16 API endpoints**
- **16 webhook handlers**
- **155 total workflow steps**

---

## 🎯 FOR PANEL PRESENTATION

**"Our Public Facilities Reservation System is a comprehensive integrated solution featuring:**

- **11 major processes** (5 internal + 6 hybrid)
- **8 external system integrations** across LGU departments
- **Real-time coordination** with Urban Planning, Infrastructure, Utilities, Energy, Housing, Roads, Maintenance, and Treasurer's Office
- **Complete transparency** from facility construction to payment verification
- **AI-powered insights** for resource optimization
- **Full accountability** with itemized reports and OR numbers

This represents a **truly integrated e-governance solution** where all LGU systems work together seamlessly to serve our citizens."

---

*Document Version: 1.0*  
*Last Updated: December 6, 2025*  
*Status: Complete ✅*

**Documentation Set Complete:**
1. ✅ INTERNAL_INTEGRATIONS.md
2. ✅ INTERNAL_PROCESSES.md
3. ✅ HYBRID_INTEGRATION_PROCESSES.md

**Total Documentation:** 3,000+ lines covering complete system architecture

