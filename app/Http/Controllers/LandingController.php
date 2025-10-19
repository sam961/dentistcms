<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequestRequest;
use App\Mail\ContactFormSubmitted;
use App\Models\ContactRequest;
use Illuminate\Support\Facades\Mail;

class LandingController extends Controller
{
    /**
     * Display the main landing page
     */
    public function index()
    {
        return view('landing.index');
    }

    /**
     * Display contact page
     */
    public function contact()
    {
        return view('landing.contact');
    }

    /**
     * Handle contact form submission
     */
    public function submitContact(StoreContactRequestRequest $request)
    {
        // Create contact request record
        $contactRequest = ContactRequest::create($request->validated());

        // Send email notification to admin
        Mail::to('sam.00961@gmail.com')->send(new ContactFormSubmitted($contactRequest));

        return redirect()->route('landing.contact')->with('success', 'Thank you for contacting us! We will get back to you within 24 hours.');
    }
}
