<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Modules\School\Entities\Student;

class FrontendController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $body_class = '';
        $student_count = Student::count();

        return view("frontend.index",
            compact('body_class','student_count')
        );

    }

    /**
     * Show the about.
     *
     * @return \Illuminate\Http\Response
     */
    public function registration()
    {
        $body_class = '';

        return view("school::frontend.students.create",
            compact('body_class')
        );

    }

    /**
     * Show the gallery.
     *
     * @return \Illuminate\Http\Response
     */
    public function gallery()
    {
        $body_class = '';

        return view("frontend.gallery",
            compact('body_class')
        );

    }

    /**
     * Privacy Policy Page.
     *
     * @return \Illuminate\Http\Response
     */
    public function privacy()
    {
        $body_class = '';

        return view('frontend.privacy', compact('body_class'));
    }

    /**
     * Terms & Conditions Page.
     *
     * @return \Illuminate\Http\Response
     */
    public function terms()
    {
        $body_class = '';

        return view('frontend.terms', compact('body_class'));
    }
}
