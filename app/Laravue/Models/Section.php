<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'department_id',
        'grade_id',
        'order',
    ];

    public function department()
    {
        return $this->belongsTo('App\Laravue\Models\Department','department_id','id');
    }
    public function grade()
    {
        return $this->belongsTo('App\Laravue\Models\Grade','grade_id','id');
    }
}
