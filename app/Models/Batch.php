<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = ['course_id', 'name', 'start_date', 'schedule', 'venue', 'seats', 'status'];

    protected $casts = ['start_date' => 'date'];

    public function course()        { return $this->belongsTo(Course::class); }
    public function registrations() { return $this->hasMany(OfflineRegistration::class); }
}
