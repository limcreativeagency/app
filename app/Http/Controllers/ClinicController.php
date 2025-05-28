<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use Illuminate\Support\Facades\Auth;

class ClinicController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user || !$user->hospital) {
            abort(403, 'Yetkisiz erişim veya hastane kaydı bulunamadı.');
        }
        $hospital = $user->hospital;
        $showMenus = in_array($user->role_id, [2, 3, 4]);
        return view('clinic.dashboard', compact('hospital', 'showMenus', 'user'));
    }

    public function editHospital()
    {
        $hospital = Auth::user()->hospital;
        return view('clinic.hospital_edit', compact('hospital'));
    }

    public function updateHospital(Request $request)
    {
        $hospital = Auth::user()->hospital;
        $validated = $request->validate([
            'clinic_name' => 'required|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        // Logo yükleme
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('hospitals', 'public');
            $validated['logo'] = $logoPath;
        }

        $hospital->update($validated);
        return redirect()->route('clinic.dashboard')->with('success', __('clinic.hospital_updated'));
    }
} 