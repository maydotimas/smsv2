<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function grade()
    {
        return $this->hasMany('App\Laravue\Models\Grade','department_id','id');
    }
    public function section()
    {
        return $this->hasMany('App\Laravue\Models\Section','department_id','id');
    }



}
