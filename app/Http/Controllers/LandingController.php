<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function about()
    {
        return view('landing.about');
    }

    public function manufacturing()
    {
        return view('landing.manufacturing');
    }

    public function service()
    {
        return view('landing.services');
    }

    public function contact()
    {
        return view('landing.contact');
    }
}
