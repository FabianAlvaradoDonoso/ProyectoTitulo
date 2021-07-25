<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Career extends Eloquent
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'careers';
    protected $fillable = [
        'name','code'
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
