<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'registration_id',
        'recipient_name', 'source', 'certificate_number', 'issued_at',
    ];

    protected $casts = ['issued_at' => 'datetime'];

    public function user()         { return $this->belongsTo(User::class); }
    public function course()       { return $this->belongsTo(Course::class); }
    public function registration() { return $this->belongsTo(OfflineRegistration::class, 'registration_id'); }

    // Name to print on the certificate (works for offline recipients without an account)
    public function getDisplayNameAttribute(): string
    {
        return $this->recipient_name ?: ($this->user->name ?? 'Student');
    }
}
