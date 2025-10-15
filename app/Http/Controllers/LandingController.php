<?php

namespace App\Http\Controllers;

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
     * Display features page
     */
    public function features()
    {
        return view('landing.features');
    }

    /**
     * Display pricing page
     */
    public function pricing()
    {
        return view('landing.pricing');
    }

    /**
     * Display contact page
     */
    public function contact()
    {
        return view('landing.contact');
    }
}
