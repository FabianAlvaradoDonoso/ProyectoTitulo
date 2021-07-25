<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Career_Course extends Eloquent
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'career_courses';
    protected $fillable = [
        'career','course'
    ];
}
