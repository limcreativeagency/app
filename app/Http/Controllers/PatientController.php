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
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with(['user', 'emergencyContacts'])->latest()->paginate(10);
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        \Log::info('Patient store başladı');
        \Log::info('Request data:', $request->all());

        try {
            // JSON verilerini düzenleme
            $allergies = $this->prepareJsonData($request->allergies);
            $chronicDiseases = $this->prepareJsonData($request->chronic_diseases);
            $medicationsUsed = $this->prepareJsonData($request->medications_used);

            \Log::info('JSON veriler hazırlandı', [
                'allergies' => $allergies,
                'chronicDiseases' => $chronicDiseases,
                'medications_used' => $medicationsUsed
            ]);

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|max:20|unique:users',
                'identity_number' => 'nullable|string|max:11|unique:patients,identity_number',
                'birth_date' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'medical_history' => 'nullable|string',
                'blood_type' => 'nullable|string|max:10',
                'profile_photo' => 'nullable|string',
                'notes' => 'nullable|string',
                'height' => 'nullable|integer|min:1|max:300',
                'weight' => 'nullable|integer|min:1|max:500',
                'smoking_status' => 'nullable|in:never,former,current',
                'alcohol_consumption' => 'nullable|in:never,occasional,regular,former',
                'exercise_status' => 'nullable|in:none,occasional,regular',
                'dietary_habits' => 'nullable|string',
                'occupation' => 'nullable|string|max:100',
                'marital_status' => 'nullable|in:single,married,divorced,widowed',
                'allergies' => 'nullable',
                'chronic_diseases' => 'nullable',
                'medications_used' => 'nullable',
                'emergency_contacts' => 'nullable|array',
                'emergency_contacts.*.name' => 'required_with:emergency_contacts|string|max:255',
                'emergency_contacts.*.phone' => 'required_with:emergency_contacts|string|max:20',
                'emergency_contacts.*.relation' => 'required_with:emergency_contacts|string|max:50'
            ]);

            \Log::info('Validasyon başarılı');

            DB::beginTransaction();

            // Generate verification code
            $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($verificationCode),
                'phone' => $request->phone,
                'role_id' => Role::where('slug', 'patient')->first()->id,
                'is_active' => false,
                'hospital_id' => auth()->user()->hospital_id,
            ]);

            \Log::info('User oluşturuldu', ['user_id' => $user->id]);

            // Create patient
            $patientData = [
                'user_id' => $user->id,
                'identity_number' => $request->identity_number,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'address' => $request->address,
                'city' => $request->city,
                'country' => $request->country,
                'postal_code' => $request->postal_code,
                'medical_history' => $request->medical_history,
                'allergies' => $allergies,
                'chronic_diseases' => $chronicDiseases,
                'medications_used' => $medicationsUsed,
                'blood_type' => $request->blood_type,
                'notes' => $request->notes,
                'height' => $request->height,
                'weight' => $request->weight,
                'smoking_status' => $request->smoking_status,
                'alcohol_consumption' => $request->alcohol_consumption,
                'exercise_status' => $request->exercise_status,
                'dietary_habits' => $request->dietary_habits,
                'occupation' => $request->occupation,
                'marital_status' => $request->marital_status,
                'is_verified' => false,
                'verification_code' => $verificationCode,
                'verification_code_expires_at' => now()->addHours(24),
            ];

            \Log::info('Patient verisi hazırlandı', $patientData);

            try {
                $patient = Patient::create($patientData);
                \Log::info('Patient oluşturuldu', ['patient_id' => $patient->id]);

                // Save emergency contacts
                if ($request->has('emergency_contacts')) {
                    foreach ($request->emergency_contacts as $contact) {
                        if (!empty($contact['name']) || !empty($contact['phone']) || !empty($contact['relation'])) {
                            $patient->emergencyContacts()->create([
                                'name' => $contact['name'],
                                'phone' => $contact['phone'],
                                'relation' => $contact['relation']
                            ]);
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Patient oluşturma hatası: ' . $e->getMessage());
                \Log::error('SQL: ' . $e->getTraceAsString());
                throw $e;
            }

            DB::commit();
            \Log::info('İşlem başarıyla tamamlandı');

            return redirect()->route('patients.verify', $user->id)
                ->with('success', 'Hasta kaydı oluşturuldu. Lütfen telefon numaranızı doğrulayın.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Hasta kaydı hatası: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()
                ->withInput()
                ->with('error', 'Hasta kaydı oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function show(Patient $patient)
    {
        $patient->load(['user', 'emergencyContacts']);
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
            'identity_number' => 'nullable|string|max:11|unique:patients,identity_number,' . $patient->id,
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'medical_history' => 'nullable|string',
            'blood_type' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
            'height' => 'nullable|integer|min:1|max:300',
            'weight' => 'nullable|integer|min:1|max:500',
            'smoking_status' => 'nullable|in:never,former,current',
            'alcohol_consumption' => 'nullable|in:never,occasional,regular,former',
            'exercise_status' => 'nullable|in:none,occasional,regular',
            'dietary_habits' => 'nullable|string',
            'occupation' => 'nullable|string|max:100',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'allergies' => 'nullable',
            'chronic_diseases' => 'nullable',
            'medications_used' => 'nullable',
            'emergency_contacts' => 'nullable|array',
            'emergency_contacts.*.name' => 'required_with:emergency_contacts|string|max:255',
            'emergency_contacts.*.phone' => 'required_with:emergency_contacts|string|max:20',
            'emergency_contacts.*.relation' => 'required_with:emergency_contacts|string|max:50'
        ]);

        DB::beginTransaction();
        try {
            // Update user
            $patient->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

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
                'allergies' => $this->prepareJsonData($request->allergies),
                'chronic_diseases' => $this->prepareJsonData($request->chronic_diseases),
                'medications_used' => $this->prepareJsonData($request->medications_used),
                'blood_type' => $request->blood_type,
                'notes' => $request->notes,
                'height' => $request->height,
                'weight' => $request->weight,
                'smoking_status' => $request->smoking_status,
                'alcohol_consumption' => $request->alcohol_consumption,
                'exercise_status' => $request->exercise_status,
                'dietary_habits' => $request->dietary_habits,
                'occupation' => $request->occupation,
                'marital_status' => $request->marital_status,
            ]);

            // Update emergency contacts
            $patient->emergencyContacts()->delete(); // Remove existing contacts
            if ($request->has('emergency_contacts')) {
                foreach ($request->emergency_contacts as $contact) {
                    if (!empty($contact['name']) || !empty($contact['phone']) || !empty($contact['relation'])) {
                        $patient->emergencyContacts()->create([
                            'name' => $contact['name'],
                            'phone' => $contact['phone'],
                            'relation' => $contact['relation']
                        ]);
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Hasta bilgileri başarıyla güncellendi.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Hasta güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function destroy(Patient $patient)
    {
        DB::beginTransaction();
        try {
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

    /**
     * JSON verilerini hazırla
     */
    private function prepareJsonData($data)
    {
        if (empty($data)) {
            return null;
        }

        // Eğer veri zaten bir array ise
        if (is_array($data)) {
            return array_map(function($item) {
                return is_array($item) ? $item['value'] : $item;
            }, array_filter($data));
        }

        // Eğer veri JSON string ise (Tagify formatı)
        if (is_string($data) && $this->isJson($data)) {
            $decoded = json_decode($data, true);
            if (is_array($decoded)) {
                return array_map(function($item) {
                    return is_array($item) && isset($item['value']) ? $item['value'] : $item;
                }, array_filter($decoded));
            }
            return $decoded;
        }

        // Virgülle ayrılmış string ise
        if (is_string($data)) {
            $items = array_map('trim', explode(',', $data));
            return array_filter($items);
        }

        return null;
    }

    /**
     * Verinin JSON olup olmadığını kontrol et
     */
    private function isJson($string) {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    protected function preparePatientData($request)
    {
        $patientData = [
            'user_id' => auth()->id(),
            'identity_number' => $request->identity_number,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'medical_history' => $request->medical_history,
            'blood_type' => $request->blood_type,
            'notes' => $request->notes,
            'height' => $request->height,
            'weight' => $request->weight,
            'smoking_status' => $request->smoking_status,
            'alcohol_consumption' => $request->alcohol_consumption,
            'exercise_status' => $request->exercise_status,
            'dietary_habits' => $request->dietary_habits,
            'occupation' => $request->occupation,
            'marital_status' => $request->marital_status,
            'is_verified' => false,
            'verification_code' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'verification_code_expires_at' => now()->addDay()
        ];

        // JSON alanlarını hazırla
        $patientData['allergies'] = json_encode($request->allergies ?: []);
        $patientData['chronic_diseases'] = json_encode($request->chronic_diseases ?: []);
        $patientData['medications_used'] = json_encode($request->medications_used ?: []);

        return $patientData;
    }

    protected function getValidationRules()
    {
        return [
            'identity_number' => 'required|string|max:20|unique:patients,identity_number',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'medical_history' => 'nullable|string',
            'blood_type' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
            'height' => 'nullable|integer|min:1|max:300',
            'weight' => 'nullable|integer|min:1|max:500',
            'smoking_status' => 'nullable|string|in:never,former,current',
            'alcohol_consumption' => 'nullable|string',
            'exercise_status' => 'nullable|string',
            'dietary_habits' => 'nullable|string',
            'occupation' => 'nullable|string|max:100',
            'marital_status' => 'nullable|string|in:single,married,divorced,widowed',
            'allergies' => 'nullable',
            'chronic_diseases' => 'nullable',
            'medications_used' => 'nullable',
            'emergency_contacts' => 'nullable|array',
            'emergency_contacts.*.name' => 'required_with:emergency_contacts|string|max:255',
            'emergency_contacts.*.phone' => 'required_with:emergency_contacts|string|max:20',
            'emergency_contacts.*.relation' => 'required_with:emergency_contacts|string|max:50'
        ];
    }
} 