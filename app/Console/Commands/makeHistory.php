<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\School\Entities\Student;

class makeHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'history:makestring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'makestring history';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $students = Student::all();
        foreach($students as $student){
            $unit_origin_array =explode(",",$student->unit_origin);
            $year_asc =array_reverse(explode(",",$student->year_graduate));
            $history_array= [];
            for($i=0;$i<count($year_asc);$i++){
                $history_array[] = $year_asc[$i]."=>".$unit_origin_array[$i];
            }
            $student->history_string =implode(',', $history_array);
            $student->save();

        }
        $count =$students->count();
        echo "success on $count students\n";
        return 0;
    }
}
