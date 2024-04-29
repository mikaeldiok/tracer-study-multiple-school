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

        $alumniArray = [];

        $years = array_reverse(range(1900, date('Y')));
        foreach($years as $year){
            $countKBTK = Student::where('history_string', 'like', "%$year=>1%")->count();
            $countSD = Student::where('history_string', 'like', "%$year=>2%")->count();
            $countSMP = Student::where('history_string', 'like', "%$year=>3%")->count();
            $countSMA = Student::where('history_string', 'like', "%$year=>4%")->count();
            $countSMK = Student::where('history_string', 'like', "%$year=>5%")->count();

            if(($countKBTK+$countSD+$countSMP+$countSMA+$countSMK) > 0){
                $alumniArray[$year] = [
                    "KB/TK" => $countKBTK,
                    "SD" => $countSD,
                    "SMP" => $countSMP,
                    "SMA" => $countSMA,
                    "SMK" => $countSMK,
                    "total" => $countKBTK+$countSD+$countSMP+$countSMA+$countSMK
                ];
            }
        }

        return view('backend.index',compact('alumni_count','alumni_count_work','alumniArray'));
    }
}
