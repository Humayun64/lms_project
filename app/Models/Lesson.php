<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'section_id',
        'title',
        'type',
        'video_source',
        'video_url',
        'video_file',
        'content',
        'file_path',
        'duration',
        'pass_mark',
        'is_preview',
        'order',
    ];

    protected $casts = [
        'is_preview' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    public function completions()
    {
        return $this->hasMany(LessonCompletion::class);
    }

    // Convert a YouTube/Vimeo link into an embeddable URL for the player
    public function getEmbedUrlAttribute(): ?string
    {
        $url = $this->video_url;
        if (!$url) {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([A-Za-z0-9_-]{11})/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return $url;
    }
}
