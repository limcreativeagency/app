<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('user')->latest()->paginate(10);
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'identity_number' => 'nullable|string|max:20|unique:patients',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|array',
            'allergies.*' => 'nullable|string|max:255',
            'chronic_diseases' => 'nullable|array',
            'chronic_diseases.*' => 'nullable|string|max:255',
            'blood_type' => 'nullable|string|max:10',
            'medications_used' => 'nullable|array',
            'medications_used.*' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
        ]);

        // Telefon numarasını normalize et
        $phone = preg_replace('/\D+/', '', $request->phone);
        if (strpos($phone, '90') !== 0) {
            $phone = '90' . $phone;
        }
        $phone = '+' . $phone;

        // Geçici şifre oluştur
        $tempPassword = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            // Create user with temporary password and inactive status
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($tempPassword),
                'phone' => $phone,
                'role_id' => Role::where('slug', 'patient')->first()->id,
                'is_active' => false, // Hasta pasif olarak oluşturulur
            ]);

            // Handle profile photo upload
            $profilePhoto = null;
            if ($request->hasFile('profile_photo')) {
                $profilePhoto = $request->file('profile_photo')->store('patients/photos', 'public');
            }

            // Create patient
            $patient = Patient::create([
                'user_id' => $user->id,
                'identity_number' => $request->identity_number,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'address' => $request->address,
                'city' => $request->city,
                'country' => $request->country,
                'postal_code' => $request->postal_code,
                'medical_history' => $request->medical_history,
                'allergies' => $request->allergies,
                'chronic_diseases' => $request->chronic_diseases,
                'blood_type' => $request->blood_type,
                'medications_used' => $request->medications_used,
                'profile_photo' => $profilePhoto,
                'notes' => $request->notes,
            ]);

            // SMS doğrulama kodu oluştur ve session'a kaydet
            $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            Session::put('patient_verification', [
                'user_id' => $user->id,
                'code' => $verificationCode,
                'phone' => $phone,
                'temp_password' => $tempPassword,
                'expires_at' => now()->addMinutes(10)
            ]);

            // TODO: SMS gönderme işlemi burada yapılacak
            // Örnek: SMS::send($phone, "Doğrulama kodunuz: {$verificationCode}");

            DB::commit();
            return redirect()->route('patients.verify', $user->id)
                ->with('success', 'Hasta kaydı oluşturuldu. Lütfen telefon numaranıza gönderilen doğrulama kodunu giriniz.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Hasta oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function show(Patient $patient)
    {
        $patient->load('user');
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $patient->load('user');
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $patient->user_id,
            'phone' => 'nullable|string|max:20',
            'identity_number' => 'nullable|string|max:20|unique:patients,identity_number,' . $patient->id,
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|array',
            'allergies.*' => 'nullable|string|max:255',
            'chronic_diseases' => 'nullable|array',
            'chronic_diseases.*' => 'nullable|string|max:255',
            'blood_type' => 'nullable|string|max:10',
            'medications_used' => 'nullable|array',
            'medications_used.*' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Update user
            $patient->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($patient->profile_photo) {
                    Storage::disk('public')->delete($patient->profile_photo);
                }
                $profilePhoto = $request->file('profile_photo')->store('patients/photos', 'public');
            } else {
                $profilePhoto = $patient->profile_photo;
            }

            // Update patient
            $patient->update([
                'identity_number' => $request->identity_number,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'address' => $request->address,
                'city' => $request->city,
                'country' => $request->country,
                'postal_code' => $request->postal_code,
                'medical_history' => $request->medical_history,
                'allergies' => $request->allergies,
                'chronic_diseases' => $request->chronic_diseases,
                'blood_type' => $request->blood_type,
                'medications_used' => $request->medications_used,
                'profile_photo' => $profilePhoto,
                'notes' => $request->notes,
            ]);

            DB::commit();
            return redirect()->route('patients.index')->with('success', 'Hasta bilgileri başarıyla güncellendi.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Hasta güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function destroy(Patient $patient)
    {
        DB::beginTransaction();
        try {
            // Delete profile photo if exists
            if ($patient->profile_photo) {
                Storage::disk('public')->delete($patient->profile_photo);
            }

            // Delete patient and associated user
            $patient->delete();
            $patient->user->delete();

            DB::commit();
            return redirect()->route('patients.index')->with('success', 'Hasta başarıyla silindi.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Hasta silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    // SMS doğrulama sayfasını göster
    public function showVerification($userId)
    {
        $verification = Session::get('patient_verification');
        if (!$verification || $verification['user_id'] != $userId || now()->isAfter($verification['expires_at'])) {
            return redirect()->route('patients.index')
                ->with('error', 'Doğrulama süresi doldu veya geçersiz doğrulama.');
        }

        return view('patients.verify', compact('userId'));
    }

    // SMS doğrulama kodunu kontrol et
    public function verify(Request $request, $userId)
    {
        $request->validate([
            'verification_code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $verification = Session::get('patient_verification');
        if (!$verification || $verification['user_id'] != $userId || now()->isAfter($verification['expires_at'])) {
            return redirect()->route('patients.index')
                ->with('error', 'Doğrulama süresi doldu veya geçersiz doğrulama.');
        }

        if ($request->verification_code !== $verification['code']) {
            return back()->with('error', 'Geçersiz doğrulama kodu.');
        }

        DB::beginTransaction();
        try {
            $user = User::findOrFail($userId);
            $user->update([
                'password' => Hash::make($request->password),
                'is_active' => true,
            ]);

            Session::forget('patient_verification');
            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Hesabınız başarıyla aktifleştirildi. Şimdi giriş yapabilirsiniz.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Doğrulama sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }
} 