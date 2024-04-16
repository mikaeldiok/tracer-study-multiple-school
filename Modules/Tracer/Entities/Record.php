<?php

namespace Modules\Tracer\Entities;

use Auth;
use DB;
use Carbon\Carbon;
use App\Models\BaseModel;
use App\Models\Traits\HasHashedMediaTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Entities\Unit;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

use Modules\Tracer\Database\factories\RecordFactory;

class Record extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "records";

    protected static $logName = 'records';
    protected static $logOnlyDirty = true;
    protected static $logAttributes = ['name', 'id'];

    public function unit(){
        return $this->belongsTo(Unit::class,'level_id');
    }
}

