<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
    ];

    // A category (School) has many courses
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
