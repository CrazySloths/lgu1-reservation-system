<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\CitizenFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HelpFaqController extends Controller
{
    /**
     * Display the help and FAQ page for citizens.
     */
    public function index()
    {
        // FAQ data organized by category
        $faqs = [
            [
                'category' => 'Getting Started',
                'questions' => [
                    [
                        'question' => 'How do I make my first facility reservation?',
                        'answer' => 'Click "New Reservation" from the sidebar, select your desired facility, choose an available date and time, fill out the booking form with event details, upload required documents, and submit your request.'
                    ],
                    [
                        'question' => 'What happens after I submit a reservation request?',
                        'answer' => 'Your request will be reviewed by staff who will verify your documents. You\'ll receive a notification once your booking is approved or if additional information is needed. Approved bookings will have a payment slip generated.'
                    ],
                    [
                        'question' => 'How long does the approval process take?',
                        'answer' => 'Most reservations are reviewed within 24-48 hours. Complex requests or those requiring additional verification may take longer. Check your Reservation History for status updates.'
                    ],
                ]
            ],
            [
                'category' => 'Reservations & Booking',
                'questions' => [
                    [
                        'question' => 'Can I book multiple facilities at once?',
                        'answer' => 'Each facility requires a separate booking request. However, you can submit multiple requests for different facilities for the same event date if needed.'
                    ],
                    [
                        'question' => 'How far in advance can I make a reservation?',
                        'answer' => 'You can typically book facilities up to 3-6 months in advance. Check the View Availability calendar to see open slots for your desired dates.'
                    ],
                    [
                        'question' => 'Can I cancel or modify my reservation?',
                        'answer' => 'To cancel or modify a reservation, contact the admin office as soon as possible. Cancellation policies may apply depending on how close to the event date you cancel.'
                    ],
                    [
                        'question' => 'What if my preferred date is already booked?',
                        'answer' => 'Use the View Availability feature to find alternative dates. You can also check the bulletin board for announcements about cancellations or newly available slots.'
                    ],
                ]
            ],
            [
                'category' => 'Payments',
                'questions' => [
                    [
                        'question' => 'How do I pay for my approved reservation?',
                        'answer' => 'Once approved, a payment slip will be generated and available in your Payment Slips section. Download the slip and pay at the designated payment office within the specified timeframe.'
                    ],
                    [
                        'question' => 'What payment methods are accepted?',
                        'answer' => 'Payment methods vary by facility. Common options include cash, check, or online payment. Refer to your payment slip for specific instructions and accepted methods.'
                    ],
                    [
                        'question' => 'When is payment due?',
                        'answer' => 'Payment is typically due within 7 days of approval or before the event date, whichever comes first. Check your payment slip for the exact due date.'
                    ],
                    [
                        'question' => 'What happens if I don\'t pay on time?',
                        'answer' => 'Unpaid reservations may be cancelled, and the time slot may be released to other citizens. Always pay before the due date to secure your booking.'
                    ],
                ]
            ],
            [
                'category' => 'Required Documents',
                'questions' => [
                    [
                        'question' => 'What documents do I need to submit?',
                        'answer' => 'Standard requirements include: Valid government-issued ID, Barangay Certificate of Residency, proof of payment (if applicable), and event permit for large gatherings. Requirements may vary by facility type.'
                    ],
                    [
                        'question' => 'Can someone else make a reservation on my behalf?',
                        'answer' => 'Yes, but an authorized representative letter signed by you must be submitted along with copies of both your ID and the representative\'s ID.'
                    ],
                    [
                        'question' => 'My documents were rejected. What should I do?',
                        'answer' => 'Check the rejection reason in your Reservation History. Common issues include unclear images, expired IDs, or missing documents. Resubmit with corrected documents.'
                    ],
                ]
            ],
            [
                'category' => 'Facility Rules & Policies',
                'questions' => [
                    [
                        'question' => 'What are the general facility usage rules?',
                        'answer' => 'Common rules include: No smoking, maintain cleanliness, respect capacity limits, end events on time, no unauthorized modifications to the facility, and proper waste disposal.'
                    ],
                    [
                        'question' => 'Am I responsible for facility damage?',
                        'answer' => 'Yes, you are responsible for any damage that occurs during your reservation period. Report any issues immediately and be prepared for potential charges for repairs.'
                    ],
                    [
                        'question' => 'Can I extend my reservation time?',
                        'answer' => 'Extensions depend on availability and must be requested in advance. Contact the admin office to check if your desired time slot can be extended.'
                    ],
                ]
            ],
        ];

        // Contact support information
        $contacts = [
            [
                'title' => 'Reservation Support',
                'department' => 'Facility Management Office',
                'phone' => '+63 XXX XXX XXXX',
                'email' => 'reservations@lgu1.com',
                'hours' => 'Monday-Friday, 8:00 AM - 5:00 PM'
            ],
            [
                'title' => 'Payment Inquiries',
                'department' => 'Cashier Office',
                'phone' => '+63 XXX XXX XXXX',
                'email' => 'payments@lgu1.com',
                'hours' => 'Monday-Friday, 8:00 AM - 4:00 PM'
            ],
            [
                'title' => 'Technical Support',
                'department' => 'IT Support',
                'phone' => '+63 XXX XXX XXXX',
                'email' => 'support@lgu1.com',
                'hours' => 'Monday-Friday, 8:00 AM - 5:00 PM'
            ],
        ];

        return view('citizen.help-faq', compact('faqs', 'contacts'));
    }

    /**
     * Submit a question from a citizen.
     */
    public function submitQuestion(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'category' => 'required|string',
            'question' => 'required|string|max:1000',
        ]);

        try {
            // Save feedback to database
            $feedback = CitizenFeedback::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'category' => $validated['category'],
                'question' => $validated['question'],
                'status' => 'pending'
            ]);

            // Log the submission
            Log::info('Citizen question submitted', [
                'id' => $feedback->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'category' => $validated['category']
            ]);

            // In a real system, you would send email notification to admin staff here
            // Mail::to('admin@lgu1.com')->send(new NewFeedbackMail($feedback));
            
            return redirect()
                ->route('citizen.help-faq')
                ->with('success', 'Your question has been submitted successfully! Our staff will respond to your email within 24 hours.');
        } catch (\Exception $e) {
            Log::error('Error submitting citizen question', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'There was an error submitting your question. Please try again or contact us directly.');
        }
    }
}
