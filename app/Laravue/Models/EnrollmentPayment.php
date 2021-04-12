<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnrollmentPayment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_year_id',
        'enrollment_id',
        'student_id',
        'payment_month',
        'payment_tf_amount',
        'payment_misc_amount',
        'payment_due',
        'interest',
        'accomplished_date',
        'status',
        'total_amount',
        'payment_no',
    ];
    public function schoolyear()
    {
        return $this->belongsTo('App\Laravue\Models\SchoolYear','school_year_id','id');
    }
    public function enrollment()
    {
        return $this->belongsTo('App\Laravue\Models\Enrollment','enrollment_id','id');
    }
    public function student()
    {
        return $this->belongsTo('App\Laravue\Models\Student','student_id','id');
    }
}
