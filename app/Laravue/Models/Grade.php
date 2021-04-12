<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'department_id',
        'order',
    ];

    public function grade()
    {
        return $this->belongsTo('App\Laravue\Models\Grade','department_id','id');
    }

    public function section()
    {
        return $this->hasMany('App\Laravue\Models\Section','grade_id','id');
    }
}
