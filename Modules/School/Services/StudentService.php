<?php

namespace Modules\School\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Modules\School\Entities\Core;
use Modules\School\Entities\Student;
use Modules\Recruiter\Entities\Booking;

use Exception;
use Carbon\Carbon;
use Auth;

use ConsoleTVs\Charts\Classes\Echarts\Chart;
use App\Charts\StudentPerStatus;
use App\Exceptions\GeneralException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


use Modules\School\Imports\StudentsImport;
use Modules\School\Events\StudentRegistered;

use App\Events\Backend\UserCreated;
use App\Events\Backend\UserUpdated;

use App\Models\User;
use App\Models\Userprofile;

class StudentService{

    public $module_name;
    public $module_title;

    public function __construct()
        {
        $this->module_title = Str::plural(class_basename(Student::class));
        $this->module_name = Str::lower($this->module_title);

        }

    public function list(){

        Log::info(label_case($this->module_title.' '.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        $student =Student::query()->orderBy('id','desc')->get();

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $student,
        );
    }

    public function getAllStudents(){

        $student =Student::query()->available()->orderBy('id','desc')->get();

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $student,
        );
    }

    public function getPopularStudent(){

        $student =DB::table('bookings')
                    ->select('bookings.student_id','name','', DB::raw('count(*) as total'))
                    ->join('students', 'bookings.student_id', '=', 'students.id')
                    ->groupBy('bookings.student_id')
                    ->orderBy('total','desc')
                    ->get();

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $student,
        );
    }

    public function filterStudents($pagination,$request){

        $student =Student::available();

        if(count($request->all()) > 0){

            if(request()->input('year_graduate')){
                $year_graduate = request()->input('year_graduate');
                $student->where('year_graduate','LIKE',"%$year_graduate%");

                if(request()->input('unit_origin')){
                    $origin = request()->input('unit_origin');
                    $student->where('history_string', 'LIKE', "%$year_graduate=>$origin%");
                }
            }

            if($request->has('name')){
                $student->where('name', 'like', '%' . $request->input('name') . '%');
            }

            // if($request->has('unit_origin')){
            //     $student->whereIn('unit_origin', [$request->input('unit_origin')]);
            // }

            // if($request->has('year_graduate')){
            //     $student->whereIn('year_graduate', [$request->input('year_graduate')]);
            // }
        }

        $student = $student->paginate($pagination);
        $student->appends($request->all());

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $student,
        );
    }

    public function getPaginatedStudents($pagination,$request){

        $student = new LengthAwarePaginator([], 0, $pagination);
        if(count($request->all()) > 0){
            $student =Student::query()->available();
            $student = $student->paginate($pagination);
        }

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $student,
        );
    }

    public function get_student($request){

        $id = $request["id"];

        $student =Student::findOrFail($id);

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $student,
        );
    }

    public function getStudentById($id){

        $student =Student::findOrFail($id);

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $student,
        );
    }
    public function getList(){

        $student =Student::query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $student,
        );
    }


    public function create(){

       Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? '0').')');

        $createOptions = [];

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $createOptions,
        );
    }

    public function store(Request $request){

        $data = $request->all();
        DB::beginTransaction();

        if(in_array(0,$data['unit_origin'])){
            DB::rollBack();
            return (object) array(
                'error'=> true,
                'message'=> "Masukan Unit Tidak Valid!",
                'data'=> null,
            );
        }

        if(count($data['unit_origin']) != count($data['year_graduate'])){
            DB::rollBack();
            return (object) array(
                'error'=> true,
                'message'=> "Jumlah unit asal dan tahun lulus harus sama",
                'data'=> null,
            );
        }

        try {

            $user = $this->createNormalUser($request);
            if(!$user){
                DB::rollBack();
                Log::critical(label_case($this->module_title.' ON LINE '.__LINE__.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | msg: Data email sudah ada');
                return (object) array(
                    'error'=> true,
                    'message'=> "Pengguna sudah ada di dalam sistem",
                    'data'=> null,
                );
            }
            $user->assignRole('student');

            if(!$request->input('student_id')){
                $data['student_id'] = 0;
            }

            $studentObject = new Student;
            $studentObject->fill($data);
            $studentObject->user_id = $user->id;


            if($studentObject->unit_origin){
                $studentObject->unit_origin = implode(',', $studentObject->unit_origin);
            }

            if($studentObject->year_graduate){
                $studentObject->year_graduate = implode(',', $studentObject->year_graduate);
            }

            //making the history_string
            $unit_origin_array =explode(",",$studentObject->unit_origin);
            $year_asc =array_reverse(explode(",",$studentObject->year_graduate));
            $history_array= [];
            for($i=0;$i<count($year_asc);$i++){
                $history_array[] = $year_asc[$i]."=>".$unit_origin_array[$i];
            }

            $studentObject->history_string =implode(',', $history_array);

            $studentObjectArray = $studentObject->toArray();

            $student = Student::create($studentObjectArray);

            if ($request->hasFile('photo')) {
                if ($student->getMedia($this->module_name)->first()) {
                    $student->getMedia($this->module_name)->first()->delete();
                }

                $media = $student->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $student->photo = $media->getUrl();

                $student->save();
            }

        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' ON LINE '.__LINE__.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__function__)." | '".$student->name.'(ID:'.$student->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $student,
        );
    }

    public function show($id, $studentId = null){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> Student::findOrFail($id),
        );
    }

    public function edit($id){

        $student = Student::findOrFail($id);

        if($student->unit_origin){
            $student->unit_origin = explode(',', $student->unit_origin);
        }

        if($student->year_graduate){
            $student->year_graduate = explode(',', $student->year_graduate);
        }

        Log::info(label_case($this->module_title.' '.__function__)." | '".$student->name.'(ID:'.$student->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $student,
        );
    }

    public function update(Request $request,$id){

        $data = $request->all();

        DB::beginTransaction();

        if(in_array(0,$data['unit_origin'])){
            DB::rollBack();
            return (object) array(
                'error'=> true,
                'message'=> "Unit Tidak Valid!",
                'data'=> null,
            );
        }

        if(count($data['unit_origin']) != count($data['year_graduate'])){
            DB::rollBack();
            return (object) array(
                'error'=> true,
                'message'=> "Jumlah unit asal dan tahun lulus harus sama",
                'data'=> null,
            );
        }

        try{
            $student = new Student;
            $student->fill($data);

            if($student->birth_date){
                $student->birth_date = Carbon::createFromFormat('d/m/Y', $student->birth_date)->format('Y-m-d');
            }

            if($student->unit_origin){
                $student->unit_origin = implode(',', $student->unit_origin);
            }

            if($student->year_graduate){
                $student->year_graduate = implode(',', $student->year_graduate);
            }


            $unit_origin_array =explode(",",$student->unit_origin);
            $year_asc =array_reverse(explode(",",$student->year_graduate));
            $history_array= [];
            for($i=0;$i<count($year_asc);$i++){
                $history_array[] = $year_asc[$i]."=>".$unit_origin_array[$i];
            }

            $student->history_string =implode(',', $history_array);
            $old_student = Student::findOrFail($id);

            $user = $this->updateNormalUser($request,$old_student->email);

            $updating = $old_student->update($student->toArray());

            $updated_student = Student::findOrFail($id);

            if ($request->hasFile('photo')) {
                if ($updated_student->getMedia($this->module_name)->first()) {
                    $updated_student->getMedia($this->module_name)->first()->delete();
                }

                $media = $updated_student->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $updated_student->photo = $media->getUrl();

                $updated_student->save();
            }


        }catch (Exception $e){
            DB::rollBack();
            report($e);
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_student->name.'(ID:'.$updated_student->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $updated_student,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $students = Student::findOrFail($id);

            $deleted = $students->delete();
        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$students->name.', ID:'.$students->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $students,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> Student::bookingonlyTrashed()->get(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $restoring =  Student::bookingwithTrashed()->where('id',$id)->restore();
            $students = Student::findOrFail($id);
        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$students->name.", ID:".$students->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $students,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $students = Student::bookingwithTrashed()->findOrFail($id);

            $deleted = $students->forceDelete();
        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$students->name.', ID:'.$students->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $students,
        );
    }

    public function import(Request $request){
        $import = Excel::import(new StudentsImport($request), $request->file('data_file'));

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $import,
        );
    }

    public static function prepareStatusFilter(){

        $status = [];

        return $status;
    }

    public static function prepareOptions(){


        $options = [];

        return $options;
    }

    public static function prepareFilter(){

        $options = self::prepareOptions();

        $year_graduate_raw = DB::table('students')
                        ->select('year_graduate', DB::raw('count(*) as total'))
                        ->groupBy('year_graduate')
                        ->orderBy('year_graduate','desc')
                        ->get();
        $year_graduate = [];
            foreach($year_graduate_raw as $item){
                $year_graduate += [$item->year_graduate => $item->year_graduate];
                // $year_class += [$item->year_class => $item->year_class." (".$item->total.")"];
            }


        $filterOp = array(
            'year_graduate'          => $year_graduate,
        );

        return array_merge($options,$filterOp);
    }

    public function getStudentPerStatusChart(){

        $chart = new Chart;

        $raw_status_order = Core::getRawData('recruitment_status');
        $status_order = [];
        foreach($raw_status_order as $key => $value){
            $status_order += [$value => 0];
        }

        $last_key = array_key_last($status_order);
        $remove_last_status = array_pop($status_order);

        $raw_majors = Core::getRawData('major');
        $majors = [];

        foreach($raw_majors as $key => $value){
            $majors[] = $value;
        }

        foreach($majors as $major){

            $status_raw = DB::table('bookings')
                        ->select('status', DB::raw('count(*) as total'))
                        ->join('students', 'bookings.student_id', '=', 'students.id')
                        ->where('students.major',$major)
                        ->where('students.available',1)
                        ->where('status',"<>",$last_key)
                        ->groupBy('status')
                        ->orderBy('status','desc')
                        ->get();
            $status = [];

            foreach($status_raw as $item){
                $status += [$item->status => $item->total];
            }

            $status = array_merge($status_order, $status);

            [$keys, $values] = Arr::divide($status);

            $chart->labels($keys);

            $chart->dataset($major, 'bar',$values);
        }

        $chart->options([
            "xAxis" => [
                "axisLabel" => [
                    "interval" => 0,
                    "overflow" => "truncate",
                ],
            ],
            "yAxis" => [
                "minInterval" => 1
            ],
        ]);

        return $chart;
    }

    public function getDoneStudentsChart(){

        $chart = new Chart;

        $raw_status_order = Core::getRawData('recruitment_status');
        $status_order = [];
        foreach($raw_status_order as $key => $value){
            $status_order += [$value => 0];
        }

        $last_key = array_key_last($status_order);
        $remove_last_status = array_pop($status_order);

        $raw_majors = Core::getRawData('major');
        $majors = [];

        foreach($raw_majors as $key => $value){
            $majors[] = $value;
        }

        $year_class_list_raw = DB::table('students')
                                ->select('year_class')
                                ->groupBy('year_class')
                                ->orderBy('year_class','asc')
                                ->limit(8)
                                ->get();

        $year_class_list= [];


        foreach($year_class_list_raw as $item){
            $year_class_list += [$item->year_class => 0];
        }

        foreach($majors as $major){

            $year_class_raw = DB::table('bookings')
                        ->select('students.year_class', DB::raw('count(*) as total'))
                        ->join('students', 'bookings.student_id', '=', 'students.id')
                        ->distinct()
                        ->where('students.major',$major)
                        ->where('status',"=",$last_key)
                        ->groupBy('students.year_class')
                        ->orderBy('students.year_class','asc')
                        ->get();

            $year_class = [];

            foreach($year_class_raw as $item){
                $year_class += [$item->year_class => $item->total];
            }

            $year_class =  $year_class + $year_class_list;

            ksort($year_class);

            [$keys, $values] = Arr::divide($year_class);

            $chart->labels($keys);

            $chart->dataset($major, 'bar',$values);
        }

        $chart->options([
            "xAxis" => [
                "axisLabel" => [
                    "interval" => 0,
                    "overflow" => "truncate",
                ],
            ],
            "yAxis" => [
                "minInterval" => 1
            ],
        ]);

        return $chart;
    }

    public function getStudentPerYearClassChart(){

        $chart = new Chart;

        $students_active = DB::table('students')
                            ->select('year_class', DB::raw('count(*) as total'))
                            ->where('available',1)
                            ->groupBy('year_class')
                            ->orderBy('year_class','asc')
                            ->get();

        $students=[];
        foreach($students_active as $item){
            $students += [$item->year_class => $item->total];
        }

        [$keys, $values] = Arr::divide($students);

        $chart->labels($keys);

        $chart->dataset("Jumlah Alumni", 'bar',$values);

        $chart->options([
            "xAxis" => [
                "axisLabel" => [
                    "interval" => 0,
                    "overflow" => "truncate",
                ],
            ],
            "yAxis" => [
                "minInterval" => 1
            ],
        ]);

        return $chart;
    }

    public static function prepareInsight(){

        $countAllStudents = Student::all()->count();

        $raw_status= Core::getRawData('recruitment_status');
        $status = [];

        foreach($raw_status as $key => $value){
            $status[] = $value;
        }

        $countDoneStudents = Booking::where('status',end($status))->get()->count();

        $stats = (object) array(
            'status'                    => $status,
            'countAllStudents'          => $countAllStudents,
            'countDoneStudents'         => $countDoneStudents,
        );

        return $stats;
    }

    public function createNormalUser($request){

        DB::beginTransaction();

        try{
            if(!$request->is_register){
                $request->confirmed = 1;
            }
            $data_array = $request->except('_token', 'roles', 'permissions', 'password_confirmation');
            $stringName = explode(" ",$data_array['name']);
            $data_array['first_name'] = $stringName[0];
            $data_array['last_name'] = end($stringName);
            $data_array['password'] = Hash::make($request->password);

            if ($request->confirmed == 1) {
                $data_array = Arr::add($data_array, 'email_verified_at', Carbon::now());
            } else {
                $data_array = Arr::add($data_array, 'email_verified_at', null);
            }

            $user = User::create($data_array);

            // Username
            $id = $user->id;
            $username = config('app.initial_username') + $id;
            $user->username = $username;
            $user->save();

            \Log::debug($user);
            if(!$request->is_register){
                event(new UserCreated($user));
            }

        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | msg: '.$e->getMessage());
            return null;
        }

        DB::commit();

        return $user;
    }

    public function updateNormalUser($request,$old_email){

        DB::beginTransaction();

        try{
            $data_array = $request->except('_token', 'roles', 'permissions', 'password_confirmation');
            $user = User::where('email', $old_email)->first();
            if(!$user){
                $user = $this->createNormalUser($request);
                $user->assignRole('student');
                DB::commit();
                return $user;
            }

            $user->email = $data_array['email'];
            if($request->password){
                $user->password = Hash::make($request->password);
            }

            $user->save();

        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case('updateNormalUser AT '.Carbon::now().' | Function:'.__FUNCTION__).' | msg: '.$e->getMessage());
            return null;
        }

        DB::commit();

        return $user;
    }
}
