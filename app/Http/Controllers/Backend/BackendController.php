<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Modules\School\Entities\Student;

class BackendController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alumni_count = Student::count();
        $alumni_count_work = Student::whereHas('records', function ($query) {
            $query->whereHas('unit', function ($q) {
                $q->where('name', 'Bekerja');
            });
        })->count();

        return view('backend.index',compact('alumni_count','alumni_count_work'));
    }
}
