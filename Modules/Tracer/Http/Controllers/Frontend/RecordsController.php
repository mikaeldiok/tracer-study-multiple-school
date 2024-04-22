<?php

namespace Modules\Tracer\Http\Controllers\Frontend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Auth;
use Flash;
use Modules\Tracer\Http\Requests\Frontend\RecordsRequest;
use Modules\Tracer\Services\RecordService;
use Spatie\Activitylog\Models\Activity;

class RecordsController extends Controller
{
    protected $recordService;

    public function __construct(RecordService $recordService)
    {
        // Page Title
        $this->module_title = trans('menu.tracer.records');

        // module name
        $this->module_name = 'records';

        // directory path of the module
        $this->module_path = 'records';

        // module icon
        $this->module_icon = 'fas fa-user-tie';

        // module model name, path
        $this->module_model = "Modules\Record\Entities\Record";

        $this->recordService = $recordService;
    }

    /**
     * Go to record homepage
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function index()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $records = $this->recordService->getAllRecords()->data;

        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        return view(
            "tracer::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "records",'driver')
        );
    }


    /**
     * Go to record catalog
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function indexPaginated(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $records = $this->recordService->getPaginatedRecords(20,$request)->data;

        if ($request->ajax()) {
            return view("tracer::frontend.$module_name.records-card-loader", ['records' => $records])->render();
        }

        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        return view(
            "tracer::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "records",'driver')
        );
    }

    /**
     * Go to record catalog
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function filterRecords(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $records = $this->recordService->filterRecords(20,$request)->data;

        if ($request->ajax()) {
            return view("tracer::frontend.$module_name.records-card-loader", ['records' => $records])->render();
        }

    }


    /**
     * Show record details
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function show($id,$recordId)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $record = $this->recordService->show($id)->data;


        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        return view(
            "tracer::frontend.$module_name.show",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "record",'driver')
        );
    }

    public function createSrRecords($id)
    {
        if(Auth::user()->can('student_area') && !Auth::user()->isSuperAdmin()){
            $student_id = Auth::user()->student->id;

            if($id != $student_id){
                return abort(404);
            }
        }

        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Create';

        $create = $this->recordService->createSrByID($id);
        $options = $create->data;
        $student_id =  $create->student_id;

        return view(
            "tracer::frontend.$module_name.create",
            compact('module_title', 'module_name', 'module_icon', 'module_action', 'module_name_singular','options','student_id')
        );
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if(Auth::user()->can('student_area') && !Auth::user()->isSuperAdmin()){
            $student_id = Auth::user()->student->id;
            $checked_id = $request->get('student_id');

            if($checked_id != $student_id){
                return abort(404);
            }
        }

        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Store';

        $records = $this->recordService->store($request);

        $$module_name_singular = $records->data;

        if(!$records->error){
            Flash::success('<i class="fas fa-check"></i> '.label_case($module_name_singular).' Data Added Successfully!')->important();
        }else{
            Flash::error("<i class='fas fa-times-circle'></i> Error When ".$module_action." '".Str::singular($module_title)."'")->important();
        }

        $userid = auth()->user()->student->id;
        return redirect("students/$userid");
    }

    public function edit($id)
    {
        if(Auth::user()->can('student_area') && !Auth::user()->isSuperAdmin()){
            $student_id = Auth::user()->student->id;

            // if($id != $student_id){
            //     return abort(404);
            // }
        }

        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Edit';

        $records = $this->recordService->edit($id);

        $$module_name_singular = $records->data;

        $student_id =  $$module_name_singular->student->id;
        $options = $this->recordService->prepareOptions();

        return view(
            "tracer::frontend.$module_name.edit",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "$module_name_singular",'options','student_id')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function update(RecordsRequest $request, $id)
    {
        if(Auth::user()->can('student_area') && !Auth::user()->isSuperAdmin()){
            $student_id = Auth::user()->student->id;
            $checked_id = $request->get('student_id');
        }
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Update';

        $this->validate($request, [
            'photo'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $records = $this->recordService->update($request,$id);

        $$module_name_singular = $records->data;
        \Log::debug("she");
        if(!$records->error){
            Flash::success('<i class="fas fa-check"></i> '.label_case($module_name_singular).' Data Updated Successfully!')->important();
        }else{
            Flash::error("<i class='fas fa-times-circle'></i> Error When ".$module_action." '".Str::singular($module_title)."'")->important();
        }

        return redirect("students/$student_id");
    }

}
