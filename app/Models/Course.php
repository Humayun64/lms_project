<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'category_id',
        'instructor_id',
        'title',
        'slug',
        'type',
        'level',
        'language',
        'duration',
        'thumbnail',
        'short_description',
        'description',
        'outcome',
        'final_project',
        'price',
        'sale_price',
        'certificate',
        'status',
    ];

    protected $casts = [
        'certificate' => 'boolean',
        'price'       => 'decimal:2',
        'sale_price'  => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // All lessons across all sections (flat)
    public function allLessons()
    {
        return $this->sections->flatMap->lessons;
    }

    public function lessonsCount(): int
    {
        return Lesson::whereIn('section_id', $this->sections()->pluck('id'))->count();
    }
}
