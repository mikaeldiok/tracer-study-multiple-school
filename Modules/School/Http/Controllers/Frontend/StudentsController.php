<?php

namespace Modules\School\Http\Controllers\Frontend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;
use Log;
use Auth;
use Modules\School\Http\Requests\Frontend\StudentsRequest;
use Modules\School\Services\StudentService;
use Modules\Tracer\DataTables\StudentRecordsDataTable;
use Modules\Tracer\Services\RecordService;
use Spatie\Activitylog\Models\Activity;

class StudentsController extends Controller
{
    protected $studentService;
    protected $recordService;

    public function __construct(StudentService $studentService, RecordService $recordService)
    {
        // Page Title
        $this->module_title = trans('menu.school.students');

        // module name
        $this->module_name = 'students';

        // directory path of the module
        $this->module_path = 'students';

        // module icon
        $this->module_icon = 'fas fa-user-tie';

        // module model name, path
        $this->module_model = "Modules\Student\Entities\Student";

        $this->studentService = $studentService;
        $this->recordService = $recordService;
    }

    /**
     * Go to student homepage
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

        $students = collect();

        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        return view(
            "school::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "students",'driver')
        );
    }


    /**
     * Go to student catalog
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

        $students = $this->studentService->getPaginatedStudents(20,$request)->data;

        if ($request->ajax()) {
            return view("school::frontend.$module_name.students-card-loader", ['students' => $students])->render();
        }

        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        return view(
            "school::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "students",'driver')
        );
    }

    /**
     * Go to student catalog
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function filterStudents(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $students = $this->studentService->filterStudents(20,$request)->data;

        if ($request->ajax()) {
            return view("school::frontend.$module_name.students-card-loader", ['students' => $students])->render();
        }

    }


    public function create()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Create';

        $options = $this->studentService->create()->data;

        return view(
            "school::frontend.$module_name.create",
            compact('module_title', 'module_name', 'module_icon', 'module_action', 'module_name_singular','options')
        );
    }
    public function store(StudentsRequest $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Store';

        $request->request->add(['available' => false]);

        if(auth()->user()){
            if(auth()->user()->can('student_area')){

                Flash::error("<i class='fas fa-times-circle'></i> Anda tidak bisa menambahkan data ketika login")->important();
                return redirect("/students/registration");
            }
        }

        $students = $this->studentService->store($request);

        $$module_name_singular = $students->data;

        if(!$students->error){
            Flash::success('<i class="fas fa-check"></i> '.label_case($module_name_singular).' Silakan login dengan data anda!')->important();

            return redirect("/login");
        }else{
            Flash::error("<i class='fas fa-times-circle'></i> $students->message")->important();
            return redirect("/students/registration");
        }

    }
    /**
     * Show student details
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function show($id)
    {
        if(Auth::user()->can('student_area') && !Auth::user()->isSuperAdmin()){
            $student_id = Auth::user()->student->id;

            if($id != $student_id){
                return abort(404);
            }
        }
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_name_records = "records";
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';


        $dataTable = new StudentRecordsDataTable($this->recordService,$id);
        $student = $this->studentService->getStudentById($id)->data;


        return $dataTable->render("school::frontend.$module_path.show",
            compact('module_title', 'module_name', 'module_name_records', 'module_icon','student', 'module_action')
        );
    }

    public function edit($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Edit';

        $students = $this->studentService->edit($id);

        $$module_name_singular = $students->data;

        $options = [];

        return view(
            "school::frontend.$module_name.edit",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "$module_name_singular",'options')
        );
    }

    public function update(Request $request, $id)
    {
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

        $students = $this->studentService->update($request,$id);

        $$module_name_singular = $students->data;

        if(!$students->error){
            Flash::success('<i class="fas fa-check"></i> '.label_case($module_name_singular).' Data Updated Successfully!')->important();
        }else{
            Flash::error("<i class='fas fa-times-circle'></i> Error! $students->message")->important();
        }

        return redirect("students/".$id);
    }
}
