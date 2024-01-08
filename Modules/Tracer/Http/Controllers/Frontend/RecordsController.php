<?php

namespace Modules\Tracer\Http\Controllers\Frontend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Auth;
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
}
