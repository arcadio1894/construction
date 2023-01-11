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
        'enable'
    ];

    // TODO: Las relaciones
    public function user()
    {
        return $this->belongsTo('App\User');
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

    protected $dates = ['deleted_at', 'birthplace', 'admission_date', 'termination_date'];
}
