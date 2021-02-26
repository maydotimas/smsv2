<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
    'lrn',
    'student_no',
    'first_name',
    'middle_name',
    'last_name',
    'suffix',
    'nickname',
    'handedness',
    'religion',
    'address',
    'brgy',
    'town',
    'province',
    'email',
    'mobile',
    'avatar',
    'gender',
    'birthdate',
    'student_type',
    'father_id',
    'mother_id',
    'guardian_id',
    'emergency_contact',
];

    public function scopeSearch($query,$keyword){
        return $query->where('first_name','like',$keyword.'%')
            ->orWhere('last_name','like',$keyword.'%')
            ->orWhere('student_no','like',$keyword.'%')
            ->orWhere('lrn','like',$keyword.'%');
    }

    public function scopeStudentNumber($query,$student_number,$student_id=false){
        if(!$student_id){
            return $query->where('student_no','=',$student_number);
        }else{
            return $query->where('student_no','=',$student_number)
            ->where('id','!=',$student_id);
        }
    }

    public function scopeSearchStudent($query,$id,$lrn,$birthdate){
        return $query
            ->where(function($query) use ($id, $lrn) {
                $query->where('student_no', $id)
                    ->orWhere('lrn', $lrn);
            })
            ->where('birthdate','=',$birthdate);
    }

    public function father()
    {
        return $this->belongsTo('App\Laravue\Models\StudentParent','father_id','id');
    }
    public function mother()
    {
        return $this->belongsTo('App\Laravue\Models\StudentParent','mother_id','id');
    }
    public function guardian()
    {
        return $this->belongsTo('App\Laravue\Models\StudentParent','guardian_id','id');
    }
    public function emergency()
    {
        return $this->belongsTo('App\Laravue\Models\StudentParent','emergency_contact','id');
    }
}
