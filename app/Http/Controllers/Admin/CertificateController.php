<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    // List all issued certificates
    public function index()
    {
        $certificates = Certificate::with(['user', 'course'])->latest()->paginate(15);
        return view('admin.certificates.index', compact('certificates'));
    }

    // Show the certificate settings form
    public function settings()
    {
        $settings = CertificateSetting::current();
        return view('admin.certificates.settings', compact('settings'));
    }

    // Save certificate settings (logo, signature, names)
    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'academy_name'    => ['required', 'string', 'max:255'],
            'signatory_name'  => ['nullable', 'string', 'max:255'],
            'signatory_title' => ['nullable', 'string', 'max:255'],
            'logo'            => ['nullable', 'image', 'max:2048'],
            'signature'       => ['nullable', 'image', 'max:2048'],
        ]);

        $settings = CertificateSetting::current();

        if ($request->hasFile('logo')) {
            if ($settings->logo) Storage::disk('public')->delete($settings->logo);
            $data['logo'] = $request->file('logo')->store('certificate', 'public');
        }

        if ($request->hasFile('signature')) {
            if ($settings->signature) Storage::disk('public')->delete($settings->signature);
            $data['signature'] = $request->file('signature')->store('certificate', 'public');
        }

        $settings->update($data);

        return back()->with('success', 'Certificate settings saved.');
    }
}
