<?php

namespace Modules\Core\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Unit extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "units";

    protected static $logName = 'units';
    protected static $logOnlyDirty = true;
    protected static $logAttributes = ['name', 'id'];

    public function registrants()
    {
        return $this->hasMany('Modules\Registrant\Entities\Registrant');
    }

    public function records()
    {
        return $this->hasMany('Modules\School\Entities\Student');
    }

}
