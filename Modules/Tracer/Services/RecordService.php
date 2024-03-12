<?php

namespace Modules\Tracer\Services;

use Modules\Core\Entities\Unit;
use Modules\Tracer\Entities\Core;
use Modules\Tracer\Entities\Record;
use Modules\Recruiter\Entities\Booking;

use Exception;
use Carbon\Carbon;
use Auth;

use ConsoleTVs\Charts\Classes\Echarts\Chart;
use App\Charts\RecordPerStatus;
use App\Exceptions\GeneralException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


use Modules\Tracer\Imports\RecordsImport;
use Modules\Tracer\Events\RecordRegistered;

use App\Events\Backend\UserCreated;
use App\Events\Backend\UserUpdated;

use App\Models\User;
use App\Models\Userprofile;

class RecordService{

    public function __construct()
        {
        $this->module_title = Str::plural(class_basename(Record::class));
        $this->module_name = Str::lower($this->module_title);

        }

    public function list(){

        Log::info(label_case($this->module_title.' '.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        $record =Record::query()->orderBy('id','desc')->get();

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $record,
        );
    }

    public function getAllRecords(){

        $record =Record::query()->available()->orderBy('id','desc')->get();

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $record,
        );
    }

    public function filterRecords($pagination,$request){

        $record =Record::query()->available();

        if(count($request->all()) > 0){
            if($request->has('major')){
                $record->whereIn('major', $request->input('major'));
            }

        }

        $record = $record->paginate($pagination);

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $record,
        );
    }

    public function getPaginatedRecords($pagination,$request){

        $record =Record::query()->available();

        if(count($request->all()) > 0){

        }

        $record = $record->paginate($pagination);

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $record,
        );
    }

    public function get_record($request){

        $id = $request["id"];

        $record =Record::findOrFail($id);

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $record,
        );
    }

    public function getList(){

        $record =Record::query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $record,
        );
    }

    public function create(){

       Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? '0').')');

        $createOptions = $this->prepareOptions();

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $createOptions,
        );
    }

    public function createSrByID($id){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? '0').')');

         $createOptions = $this->prepareOptions();

         return (object) array(
             'error'=> false,
             'message'=> '',
             'data'=> $createOptions,
             'student_id'=> $id
         );
    }

    public function store(Request $request){

        $data = $request->all();
        DB::beginTransaction();

        try {

            $recordObject = new Record;
            $recordObject->fill($data);

            $recordObjectArray = $recordObject->toArray();

            $record = Record::create($recordObjectArray);

            if ($request->hasFile('photo')) {
                if ($record->getMedia($this->module_name)->first()) {
                    $record->getMedia($this->module_name)->first()->delete();
                }

                $media = $record->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $record->photo = $media->getUrl();

                $record->save();
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

        Log::info(label_case($this->module_title.' '.__function__)." | '".$record->name.'(ID:'.$record->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $record,
        );
    }

    public function show($id, $recordId = null){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> Record::findOrFail($id),
        );
    }

    public function edit($id){

        $record = Record::findOrFail($id);

        Log::info(label_case($this->module_title.' '.__function__)." | '".$record->name.'(ID:'.$record->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $record,
        );
    }

    public function update(Request $request,$id){

        $data = $request->all();

        DB::beginTransaction();

        try{

            $record = new Record;
            $record->fill($data);

            $updating = Record::findOrFail($id)->update($record->toArray());

            $updated_record = Record::findOrFail($id);

            if ($request->hasFile('photo')) {
                if ($updated_record->getMedia($this->module_name)->first()) {
                    $updated_record->getMedia($this->module_name)->first()->delete();
                }

                $media = $updated_record->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $updated_record->photo = $media->getUrl();

                $updated_record->save();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_record->name.'(ID:'.$updated_record->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $updated_record,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $records = Record::findOrFail($id);

            $deleted = $records->delete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$records->name.', ID:'.$records->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $records,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> Record::bookingonlyTrashed()->get(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $restoring =  Record::bookingwithTrashed()->where('id',$id)->restore();
            $records = Record::findOrFail($id);
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

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$records->name.", ID:".$records->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $records,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $records = Record::bookingwithTrashed()->findOrFail($id);

            $deleted = $records->forceDelete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$records->name.', ID:'.$records->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $records,
        );
    }

    public function import(Request $request){
        $import = Excel::import(new RecordsImport($request), $request->file('data_file'));

        return (object) array(
            'error'=> false,
            'message'=> '',
            'data'=> $import,
        );
    }

    public static function prepareStatusFilter(){

        $raw_status = Core::getRawData('recruitment_status');
        $status = [];
        foreach($raw_status as $key => $value){
            $status += [$value => $value];
        }

        return $status;
    }

    public static function prepareOptions(){

        $level = Unit::pluck('name','id');
        $options = array(
            'level'         => $level,
        );

        return $options;
    }

}
