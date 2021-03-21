<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'remarks',
        'date_enrolled',
        'status',
        'reservation_id',
        'student_id',
        'department_id',
        'grade_id',
        'section_id',
        'enrolled_by',
        'processor_id',
    ];

    public function scopeSearch($query,$keyword){
        return $query->where('first_name','like','%'.$keyword.'%')
            ->orWhere('middle_name','like','%'.$keyword.'%')
            ->orWhere('last_name','like','%'.$keyword.'%');
    }
    public function scopeActive($query,$keyword){
        return $query->whereNull('deleted_at');
    }

    public function scopeId($query,$id){
        return $query->where('id','=',$id);
    }

    public function department()
    {
        return $this->belongsTo('App\Laravue\Models\Department','department_id','id');
    }
    public function school_year()
    {
        return $this->belongsTo('App\Laravue\Models\SchoolYear','school_year_id','id');
    }
    public function grade()
    {
        return $this->belongsTo('App\Laravue\Models\Grade','grade_id','id');
    }
    public function section()
    {
        return $this->belongsTo('App\Laravue\Models\Section','section_id','id');
    }
    public function student()
    {
        return $this->belongsTo('App\Laravue\Models\Student','student_id','id');
    }
    public function student_data()
    {
        return $this->belongsTo('App\Laravue\Models\Student','student_id','id');
    }
}
