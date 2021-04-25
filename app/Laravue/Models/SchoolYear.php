<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolYear extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'year',
        'start',
        'end',
        'status',
        'is_locked',
    ];

    public function scopeSearch($query,$keyword){
        return $query->where('name','like','%'.$keyword.'%')
            ->orWhere('description','like','%'.$keyword.'%')
            ->orWhere('year','like','%'.$keyword.'%');
    }
    public function scopeActive($query,$keyword){
        return $query->where('status','=',$keyword);
    }

    public function department_fees()
    {
        return $this->hasMany('App\Laravue\Models\SchoolYearFee','school_year_id','id');
    }

}
