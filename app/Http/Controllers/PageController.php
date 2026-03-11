<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function faq()
    {
        return view('pages.faq');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function terms()
    {
        return view('pages.legal.terms');
    }

    public function privacy()
    {
        return view('pages.legal.privacy');
    }

    public function sendContact(Request $request)
    {
        return redirect()->route('pages.thanks');
    }

    public function thanks()
    {
        return view('pages.thanks');
    }
}