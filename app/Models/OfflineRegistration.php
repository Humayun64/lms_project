<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfflineRegistration extends Model
{
    protected $fillable = [
        'course_id', 'batch_id', 'user_id',
        'name', 'email', 'phone',
        'payment_method', 'paid', 'status',
    ];

    protected $casts = ['paid' => 'boolean'];

    public function course() { return $this->belongsTo(Course::class); }
    public function batch()  { return $this->belongsTo(Batch::class); }
    public function user()   { return $this->belongsTo(User::class); }
}
