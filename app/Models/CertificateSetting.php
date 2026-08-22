<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateSetting extends Model
{
    protected $fillable = [
        'academy_name', 'logo', 'signature', 'signatory_name', 'signatory_title',
    ];

    // Always get the single settings row (create a default if missing)
    public static function current(): self
    {
        return static::first() ?? static::create(['academy_name' => 'Kolom Academy']);
    }
}
