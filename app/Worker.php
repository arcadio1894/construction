<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worker extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'personal_address',
        'birthplace',
        'phone',
        'email',
        'level_school',
        'profession',
        'image',
        'dni',
        'admission_date',
        'num_children',
        'daily_salary',
        'monthly_salary',
        'pension',
        'gender',
        'essalud',
        'assign_family',
        'five_category',
        'termination_date',
        'reason_for_termination',
        'observation',
        'contract_id', // id code date_start date_fin
        'user_id',
        'civil_status_id', // id description
        'work_function_id', // id description
        'pension_system_id', // id description percentage
        'working_day_id',
        'enable',
        'area_worker_id',
        'percentage_pension_system'
    ];

    // TODO: Las relaciones
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function area_worker()
    {
        return $this->belongsTo('App\AreaWorker', 'area_worker_id');
    }

    public function contract()
    {
        return $this->belongsTo('App\Contract');
    }

    public function civil_status()
    {
        return $this->belongsTo('App\CivilStatus');
    }

    public function work_function()
    {
        return $this->belongsTo('App\WorkFunction');
    }

    public function working_day()
    {
        return $this->belongsTo('App\WorkingDay');
    }

    public function pension_system()
    {
        return $this->belongsTo('App\PensionSystem');
    }

    public function emergency_contacts()
    {
        return $this->hasMany('App\EmergencyContact');
    }

    public function getDailySalaryTotalAttribute()
    {
        $diasMes = 30;
        $assign_family = PercentageWorker::where('name', 'assign_family')->first();
        $rmv = PercentageWorker::where('name', 'rmv')->first();
        $asignacionFamiliarDiaria = ($this->num_children == 0 || $this->num_children == null) ? 0: round(($rmv->value*($assign_family->value/100))/$diasMes, 2);

        return $this->daily_salary + $asignacionFamiliarDiaria;
    }

    protected $dates = ['deleted_at', 'birthplace', 'admission_date', 'termination_date'];
}
