<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpSupportController extends Controller
{
    /**
     * Display the help and support page.
     */
    public function index()
    {
        // FAQ data
        $faqs = [
            [
                'category' => 'Verification Process',
                'questions' => [
                    [
                        'question' => 'How do I verify a booking request?',
                        'answer' => 'Go to Document Verification, click on a pending booking, review all submitted documents, check for completeness and authenticity, then approve or reject based on the verification guidelines.'
                    ],
                    [
                        'question' => 'What documents are required for verification?',
                        'answer' => 'Standard requirements include: Valid Government ID, Barangay Certificate of Residency, Payment slip/proof of payment, Event permit (if applicable), and Authorized representative letter (if not the applicant).'
                    ],
                    [
                        'question' => 'How long should verification take?',
                        'answer' => 'Aim to complete verification within 24-48 hours of submission. Urgent or time-sensitive requests should be prioritized.'
                    ],
                ]
            ],
            [
                'category' => 'Document Handling',
                'questions' => [
                    [
                        'question' => 'What if submitted documents are unclear or blurry?',
                        'answer' => 'Reject the booking with reason "Unclear Documents" and add a note requesting the applicant to resubmit clearer copies. Contact the applicant if phone number is provided.'
                    ],
                    [
                        'question' => 'What are common red flags in documents?',
                        'answer' => 'Watch for: Altered or photoshopped documents, Expired IDs, Mismatched names across documents, Missing signatures or dates, Suspicious payment receipts, and Inconsistent information.'
                    ],
                    [
                        'question' => 'Can I undo a verification?',
                        'answer' => 'No, verifications are final. Contact an administrator if you need to reverse a decision. Always double-check before approving or rejecting.'
                    ],
                ]
            ],
            [
                'category' => 'System & Technical',
                'questions' => [
                    [
                        'question' => 'Document preview is not loading. What should I do?',
                        'answer' => 'Try refreshing the page. If it persists, check your internet connection. If the problem continues, report it using the issue form below and contact technical support.'
                    ],
                    [
                        'question' => 'How do I report a suspicious booking?',
                        'answer' => 'Use the "Report Issue" form below, select "Suspicious Activity" as the type, and provide detailed information. Also contact admin immediately for urgent cases.'
                    ],
                    [
                        'question' => 'Where can I see my verification statistics?',
                        'answer' => 'Go to "My Statistics" in the sidebar to view your verification count, approval rate, and performance metrics.'
                    ],
                ]
            ],
        ];

        // Contact information
        $contacts = [
            [
                'title' => 'Admin Office',
                'name' => 'LGU1 Admin Department',
                'phone' => '+63 XXX XXX XXXX',
                'email' => 'admin@lgu1.com',
                'hours' => 'Mon-Fri, 8:00 AM - 5:00 PM'
            ],
            [
                'title' => 'Technical Support',
                'name' => 'IT Support Team',
                'phone' => '+63 XXX XXX XXXX',
                'email' => 'support@lgu1.com',
                'hours' => 'Mon-Fri, 8:00 AM - 5:00 PM'
            ],
            [
                'title' => 'Emergency Contact',
                'name' => 'Facility Manager',
                'phone' => '+63 XXX XXX XXXX',
                'email' => 'emergency@lgu1.com',
                'hours' => '24/7 Available'
            ],
        ];

        return view('staff.help-support', compact('faqs', 'contacts'));
    }

    /**
     * Submit a support ticket or issue report.
     */
    public function submitIssue(Request $request)
    {
        $request->validate([
            'issue_type' => 'required|string',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // In a real application, this would create a ticket in the database
        // For now, we'll just return success
        
        return redirect()->route('staff.help-support')
            ->with('success', 'Your issue has been reported successfully. Our support team will contact you soon.');
    }
}
