<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\School\Entities\Student;
use Modules\Tracer\Entities\Record;

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

        $alumni_count_ptn = Student::whereHas('records', function ($query) {
            $query->where('campus_status', 'PTN')
                  ->where('level_id', function ($subQuery) {
                      $subQuery->select('id')
                               ->from('units')
                               ->where('name', 'Kuliah')
                               ->limit(1);
                  })
                  ->where('records.id', function ($subQuery) {
                      $subQuery->select(DB::raw('MIN(records.id)'))
                               ->from('records')
                               ->join('units', 'units.id', '=', 'records.level_id')
                               ->where('units.name', 'Kuliah')
                               ->whereColumn('records.student_id', 'students.id')
                               ->groupBy('records.student_id')
                               ->limit(1);
                  });
        })->count();


        $alumni_count_pts = Student::whereHas('records', function ($query) {
            $query->where('campus_status', 'PTS')
                  ->where('level_id', function ($subQuery) {
                      // Fetch the 'id' from 'units' where 'name' is 'Kuliah'. Assuming there could be multiple, take the first one.
                      $subQuery->select('id')
                               ->from('units')
                               ->where('name', 'Kuliah')
                               ->limit(1);
                  })
                  ->where('records.id', function ($subQuery) {
                      // Select the minimum 'id' from 'records' that matches the 'Kuliah' unit and current student.
                      $subQuery->select(DB::raw('MIN(records.id)'))
                               ->from('records')
                               ->join('units', 'units.id', '=', 'records.level_id')
                               ->where('units.name', 'Kuliah')
                               ->whereColumn('records.student_id', 'students.id')  // Ensuring it relates to the current student
                               ->groupBy('records.student_id')  // Group by student_id to ensure we're getting the correct minimum
                               ->limit(1);
                  });
        })->count();

        $studentsWithLatestBekerja = Student::with(['records' => function ($query) {
            $query->whereHas('unit', function ($q) {
                $q->where('name', 'Bekerja');
            })
            ->latest('created_at')
            ->take(1);
        }])->get();

        // Group by income
        $latestRecordsSubquery = Record::select('student_id', DB::raw('MAX(created_at) as max_date'))
        ->whereHas('unit', function ($query) {
            $query->where('name', 'Bekerja');
        })
        ->groupBy('student_id');

        \Log::debug(json_encode($latestRecordsSubquery));
        $incomeDistributionRaw = Record::joinSub($latestRecordsSubquery, 'latest_records', function ($join) {
            $join->on('records.student_id', '=', 'latest_records.student_id')
                ->on('records.created_at', '=', 'latest_records.max_date');
        })
        ->groupBy('income')
        ->select('income as name', DB::raw('COUNT(*) as value'))
        ->get();

        $incomeDistribution = [];
        $otherIncome = 0;
        $incomeKey = config('income');
        foreach ($incomeDistributionRaw as $item) {
            if (!array_key_exists($item->name, $incomeKey)) {
                $otherIncome += $item->value;
                continue;
            }
            $incomeInfo = $incomeKey[$item->name];
            $incomeDistribution[] = [
                "name"  => "Golongan " . $incomeInfo['type'], // Assuming you still want to increment the name numerically
                "tier"  => $incomeInfo['value'], // Accessing the 'type' from the new config structure
                "value" => $item->value,
            ];
        }

        if($otherIncome > 0){
            $incomeDistribution[] = [
                "name" => "Golongan Lain",
                "tier"  => "Other",
                "value" => $otherIncome, // Changed to sum of $otherIncome calculated from unlisted types
            ];
        }

        $incomeDistribution = array_reverse($incomeDistribution);

        return view('backend.index',compact('alumni_count','alumni_count_work','alumniArray','alumni_count_ptn','alumni_count_pts','incomeDistribution'));
    }
}
