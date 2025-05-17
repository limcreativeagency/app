<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Clinic;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $request->validate([
            'clinic_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:hospitals,email',
            'tax_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
        ]);

        session(['clinic_step1' => $request->all()]);
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

        // Handle phone number formatting
        $phone = preg_replace('/\D+/', '', $request->admin_phone_visible);
        $countryCode = $request->admin_phone_country ?: '90'; // Default to Turkey if not specified
        if (strpos($phone, $countryCode) !== 0) {
            $phone = $countryCode . $phone;
        }
        $validated['admin_phone'] = '+' . $phone;

        // Store all validated data in session
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
            'verification_code' => 'required|string|size:4',
        ]);

        // Get data from previous steps
        $step1Data = Session::get('clinic_step1');
        $step2Data = Session::get('clinic_step2');

        if (!$step1Data || !$step2Data) {
            return redirect()->route('register.step1')
                ->with('error', 'Kayıt oturumu süresi doldu. Lütfen baştan başlayın.');
        }

        // Demo için sabit kod kontrolü (1234)
        if ($request->verification_code !== '1234') {
            return back()->with('error', 'Geçersiz doğrulama kodu.');
        }

        try {
            DB::beginTransaction();

            // Create hospital first
            $hospital = Hospital::create([
                'clinic_name' => $step1Data['clinic_name'],
                'phone' => $step1Data['phone'],
                'email' => $step1Data['email'],
                'tax_number' => $step1Data['tax_number'] ?? null,
                'address' => $step1Data['address'] ?? null,
                'city' => $step1Data['city'] ?? null,
                'country' => $step1Data['country'] ?? null,
                'website' => $step1Data['website'] ?? null,
                'trial_start_date' => now(),
                'trial_end_date' => now()->addDays(14),
                'status' => 'trial'
            ]);

            // Create user (clinic manager)
            $user = User::create([
                'name' => $step2Data['admin_name'],
                'email' => $step2Data['admin_email'],
                'phone' => $step2Data['admin_phone'],
                'password' => Hash::make($step2Data['admin_password']),
                'role_id' => 2, // Clinic manager role
                'hospital_id' => $hospital->id,
                'is_active' => true
            ]);

            DB::commit();

            // Clear registration session data
            Session::forget(['clinic_step1', 'clinic_step2']);

            // Log the user in
            auth()->login($user);

            return redirect()->route('clinic.dashboard')
                ->with('success', '14 günlük deneme süreniz başladı. Kaydınız başarıyla tamamlandı.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Hastane kaydı hatası: ' . $e->getMessage());
            return back()->with('error', 'Kayıt sırasında bir hata oluştu. Lütfen tekrar deneyin.');
        }
    }

    public function finalizeRegistration()
    {
        $step1Data = session('clinic_step1');
        $step2Data = session('clinic_step2');
        $step3Data = session('clinic_step3');

        if (!$step1Data || !$step2Data || !$step3Data) {
            return redirect()->route('register.step1')
                ->with('error', 'Lütfen kayıt işlemini baştan başlatın.');
        }

        DB::beginTransaction();
        try {
            // Create hospital
            $hospital = Hospital::create([
                'clinic_name' => $step1Data['clinic_name'],
                'phone' => $step1Data['phone'],
                'email' => $step1Data['email'],
                'tax_number' => $step1Data['tax_number'] ?? null,
                'address' => $step1Data['address'] ?? null,
                'city' => $step1Data['city'] ?? null,
                'country' => $step1Data['country'] ?? null,
                'website' => $step1Data['website'] ?? null,
                'trial_start_date' => now(),
                'trial_end_date' => now()->addDays(14),
                'status' => 'trial'
            ]);

            // Create user (clinic manager)
            $user = User::create([
                'name' => $step2Data['admin_name'],
                'email' => $step2Data['admin_email'],
                'phone' => $step2Data['admin_phone'],
                'password' => Hash::make($step2Data['admin_password']),
                'role_id' => 2, // Clinic manager role
                'hospital_id' => $hospital->id,
                'is_active' => true
            ]);

            DB::commit();

            // Clear registration session data
            Session::forget(['clinic_step1', 'clinic_step2']);

            // Log the user in
            auth()->login($user);

            return redirect()->route('clinic.dashboard')
                ->with('success', '14 günlük deneme süreniz başladı. Kaydınız başarıyla tamamlandı.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Hastane kaydı hatası: ' . $e->getMessage());
            return back()->with('error', 'Kayıt sırasında bir hata oluştu. Lütfen tekrar deneyin.');
        }
    }
}
