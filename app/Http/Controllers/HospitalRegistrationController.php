<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class HospitalRegistrationController extends Controller
{
    // 1. Klinik bilgi formu göster
    public function step1()
    {
        return view('registration.step1');
    }

    // 1. Klinik bilgi formu submit
    public function step1Submit(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
        ]);
        // Telefonu normalize et
        $validated['phone'] = preg_replace('/\D+/', '', $validated['phone']);
        if (strpos($validated['phone'], '90') !== 0) {
            $validated['phone'] = '90' . $validated['phone'];
        }
        $validated['phone'] = '+' . $validated['phone'];
        Session::put('clinic_step1', $validated);
        return redirect()->route('register.step2');
    }

    // 2. Yönetici bilgi formu göster
    public function step2()
    {
        return view('registration.step2');
    }

    // 2. Yönetici bilgi formu submit
    public function step2Submit(Request $request)
    {
        $validated = $request->validate([
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'admin_phone' => 'required|string|max:30|unique:users,phone',
            'admin_password' => 'required|string|min:6|confirmed',
        ]);
        // Telefonu normalize et
        $validated['admin_phone'] = preg_replace('/\D+/', '', $validated['admin_phone']);
        if (strpos($validated['admin_phone'], '90') !== 0) {
            $validated['admin_phone'] = '90' . $validated['admin_phone'];
        }
        $validated['admin_phone'] = '+' . $validated['admin_phone'];
        Session::put('clinic_step2', $validated);
        return redirect()->route('register.step3');
    }

    // 3. SMS onay ekranı göster
    public function step3()
    {
        return view('registration.step3');
    }

    // 3. SMS onay submit ve kayıt işlemi
    public function step3Submit(Request $request)
    {
        $request->validate([
            'sms_code' => 'required',
        ]);
        // Demo SMS kodu kontrolü
        if ($request->input('sms_code') !== '1234') {
            return redirect()->back()->withErrors(['sms_code' => 'Geçersiz SMS kodu! (Demo kod: 1234)']);
        }
        $clinic = Session::get('clinic_step1');
        $admin = Session::get('clinic_step2');

        // Son kontrol: email ve telefon unique mi?
        if (User::where('email', $admin['admin_email'])->exists()) {
            return redirect()->route('register.step2')->withErrors(['admin_email' => 'Bu e-posta ile zaten bir kullanıcı var.']);
        }
        if (User::where('phone', $admin['admin_phone'])->exists()) {
            return redirect()->route('register.step2')->withErrors(['admin_phone' => 'Bu telefon numarası ile zaten bir kullanıcı var.']);
        }

        $hospital = Hospital::create([
            'title' => $clinic['title'],
            'phone' => $clinic['phone'],
            'email' => $clinic['email'],
            'address' => $clinic['address'] ?? null,
            'city' => $clinic['city'] ?? null,
            'country' => $clinic['country'] ?? null,
            'website' => $clinic['website'] ?? null,
            'status' => 'trial',
        ]);
        $user = User::create([
            'name' => $admin['admin_name'],
            'email' => $admin['admin_email'],
            'phone' => $admin['admin_phone'],
            'password' => Hash::make($admin['admin_password']),
            'role_id' => 1, // örnek: superadmin veya admin rolü
            'hospital_id' => $hospital->id,
            'is_active' => true,
        ]);
        Session::forget(['clinic_step1', 'clinic_step2']);
        return redirect()->route('login')->with('success', 'Kayıt tamamlandı!');
    }
}
