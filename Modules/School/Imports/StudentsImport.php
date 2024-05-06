<?php

namespace Modules\School\Imports;

use App\Events\Backend\UserCreated;
use App\Models\User;
use ZanySoft\Zip\Zip;
use Exception;
use ZipArchive;
use Carbon\Carbon;
use Modules\School\Entities\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;
use DB;
use Illuminate\Support\Arr;


class StudentsImport implements ToCollection, WithHeadingRow
{
    protected $module_title;
    private $request;
    public function __construct(Request $request)
    {
        $this->module_title = Str::plural(class_basename(Student::class));
        $this->module_name = Str::lower($this->module_title);
        $this->request = $request;
    }

    public function collection(Collection $rows)
    {
        $zip = null;
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif']; // Add more extensions if needed

        if ($this->request->hasFile('photo_file')){
            $storezippath = \Storage::putFile('zipfile', $this->request->file('photo_file'));
            \Log::debug(storage_path("app/".$storezippath));
            $zip = new Zip();
            $zip->open($this->request->file('photo_file'));
        }

        foreach ($rows as $row) {

            $user = User::where('email',$row['email'])->first();

            if(!$user){
               $user =  $this->createNormalUser($row);
            }else{
                if(!$user->userprofile){
                    event(new UserCreated($user));
                }
            }

            $unitOrigin = "null";
            switch($row['unit_origin']){
                case "KB/TK":
                    $unitOrigin = "1";
                    break;
                case "SD":
                    $unitOrigin = "2";
                    break;
                case "SMP":
                    $unitOrigin = "3";
                    break;
                case "SMA":
                    $unitOrigin = "4";
                    break;
                case "SMK":
                    $unitOrigin = "5";
                    break;
                default:
                    $unitOrigin= "dum";
                    break;
            }

            $student = Student::updateOrCreate([
                                'email' => $row['email'],
                            ],[
                            'name'                      => $row['name'],
                            'student_id'                => $row['student_id'],
                            'year_graduate'             => $row['year_graduate'],
                            'unit_origin'               => $unitOrigin,
                            'phone'                     => $row['phone'],
                            'email'                     => $row['email'],
                            'available'                 => 1,
                            'photo'                     => "",
                            'user_id'                   => $user->id
                        ]);

            // $unit_origin_array =explode(",",$row['unit_origin']);
            // $year_asc =explode(",",$row['year_graduate']);
            // $history_array= [];
            // for($i=0;$i<count($year_asc);$i++){
            //     $history_array[] = $year_asc[$i]."=>".$unit_origin_array[$i];
            // }

            // $student->history_string =implode(',', $history_array);
            $student->history_string = $row['year_graduate']."=>".$unitOrigin;
            $student->photo = "img/default-avatar.jpg";
            $student->save();

            if($zip){

                // foreach ($imageExtensions as $extension) {
                    if($row['photo']){

                        $photoExist = $zip->has($row['photo'], ZipArchive::FL_NOCASE | ZipArchive::FL_NODIR);
                        if ($photoExist) {

                            // Extract the image file
                            $isExtracted = $zip->extract(storage_path('app/imgtmp'), $row['photo']);

                            if ($isExtracted) {
                                // Get the extracted image content
                                $photo = \Storage::get('imgtmp/' . $row['photo']);

                                // Delete any existing media
                                if ($student->getMedia($this->module_name)->first()) {
                                    $student->getMedia($this->module_name)->first()->delete();
                                }

                                // Add the extracted image as media
                                $media = $student->addMediaFromDisk('imgtmp/' . $row['photo'], 'local')->toMediaCollection($this->module_name);

                                $fullUrl = $media->getUrl();
                                $relativePath = Str::after($fullUrl, '/storage/');
                                $storagePath = '/storage/' . $relativePath;
                                // Update the photo URL in the database
                                $student->photo = $storagePath;

                                // Save the changes to the student model
                                $student->save();

                            }
                        }else{
                            $student->save();
                        }

                    }
                // }

            }

        }
    }

    public function createNormalUser($request){

        DB::beginTransaction();

        try{
            $data_array =[];
            $data_array["confirmed"] = 1;
            $data_array = $request->except('_token', 'roles', 'permissions', 'password_confirmation');
            $stringName = explode(" ",$data_array['name']);
            $data_array['first_name'] = $stringName[0];
            $data_array['last_name'] = end($stringName);
            $data_array['password'] = Hash::make($request["password"]);

            $data_array = Arr::add($data_array, 'email_verified_at', Carbon::now());

            $user = User::updateOrCreate(["email"=>$request["email"]],$data_array->toArray());

            // Username
            $id = $user->id;
            $username = config('app.initial_username') + $id;
            $user->username = $username;
            $user->save();

            event(new UserCreated($user));

        }catch (Exception $e){
            DB::rollBack();
            \Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | msg: '.$e->getMessage());
            return null;
        }

        DB::commit();

        return $user;
    }

}
