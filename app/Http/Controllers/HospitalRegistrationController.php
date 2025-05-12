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
        $validated = $request->validate([
            'clinic_name' => 'required|string|max:255',
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'country_code' => 'required|string|max:5',
        ]);

        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'country_code' => $request->country_code,
                'role_id' => 2, // Clinic role
            ]);

            // Create clinic with trial period
            $trialStartDate = Carbon::now();
            $trialEndDate = $trialStartDate->copy()->addDays(14);

            $clinic = Clinic::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'trial_start_date' => $trialStartDate,
                'trial_end_date' => $trialEndDate,
                // Add other clinic fields here
            ]);

            DB::commit();

            // Log the user in
            auth()->login($user);

            return redirect()->route('clinic.dashboard')
                ->with('success', 'Kayıt başarıyla tamamlandı. 14 günlük deneme süreniz başladı.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Kayıt sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }
}
