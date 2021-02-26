<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class StudentParent extends Model
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'email',
        'mobile',
        'occupation',
        'type',
    ];

    public function father()
    {
        return $this->belongsTo('App\Laravue\Models\Student','father_id','id');
    }
    public function mother()
    {
        return $this->hasMany('App\Laravue\Models\Student','mother_id','id');
    }
    public function guardian()
    {
        return $this->hasMany('App\Laravue\Models\Student','guardian_id','id');
    }
    public function emergency()
    {
        return $this->hasMany('App\Laravue\Models\Student','emergency_contact','id');
    }
}
