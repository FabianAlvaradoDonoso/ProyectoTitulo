<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Course_Document extends Eloquent
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'course_documents';
    protected $fillable = [
        'course', 'document'
    ];
}
