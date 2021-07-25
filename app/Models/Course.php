<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'courses';
    protected $fillable = [
        'name','code'
    ];

    public function careers()
    {
        return $this->embedsMany(Career::class);
    }


}
