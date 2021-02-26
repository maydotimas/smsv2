<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolYearFee extends Model
{
    protected $fillable = [
        'type',
        'total_tuition_fee',
        'total_misc_fee',
        'enrollment_tuition_fee',
        'enrollment_misc_fee',
        'monthly_tuition_fee',
        'monthly_misc_fee',
        'department_id',
        'grade_id',
        'school_year_id',
    ];

    public function department()
    {
        return $this->belongsTo('App\Laravue\Models\Department','department_id','id');
    }
    public function schoolyear()
    {
        return $this->belongsTo('App\Laravue\Models\SchoolYear','school_year_id','id');
    }
    public function grade()
    {
        return $this->belongsTo('App\Laravue\Models\Grade','grade_id','id');
    }
}
