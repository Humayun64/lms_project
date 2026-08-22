<?php

namespace App\Http\Controllers;

use App\Models\Certificate;

class CertificateVerifyController extends Controller
{
    public function __invoke($number)
    {
        $certificate = Certificate::with(['user', 'course'])
            ->where('certificate_number', $number)
            ->first();

        return view('certificate.verify', compact('certificate', 'number'));
    }
}
