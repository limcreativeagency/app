<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MedicationPlanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $plans = \App\Models\MedicationPlan::query()
            ->when($user->role_id === 2, function ($query) use ($user) {
                // Klinik yöneticisi için kendi hastanesindeki tüm planları göster
                return $query->whereHas('treatment.patient', function ($q) use ($user) {
                    $q->where('hospital_id', $user->hospital_id);
                });
            })
            ->when($user->role_id === 3, function ($query) use ($user) {
                // Doktor için kendi oluşturduğu planları göster
                return $query->where('user_id', $user->id);
            })
            ->when($user->role_id === 4, function ($query) use ($user) {
                // Temsilci için kendi hastanesindeki planları göster
                return $query->whereHas('treatment.patient', function ($q) use ($user) {
                    $q->where('hospital_id', $user->hospital_id);
                });
            })
            ->with(['treatment.patient', 'usages'])
            ->latest()
            ->paginate(10);

        return view('medications.index', compact('plans'));
    }

    public function store(Request $request)
    {
        \Log::info('user_id kontrol', ['user_id' => $request->user_id, 'auth_id' => auth()->id()]);
        $data = $request->validate([
            'treatment_id' => 'required|exists:treatments,id',
            'name' => 'required|string',
            'type' => 'required|in:oral,topical,spray,vitamin',
            'dose' => 'required|string',
            'instructions' => 'nullable|string',
            'times' => 'required|array',
            'times.*' => 'required|date_format:H:i',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $data['start_date'] = Carbon::createFromFormat('d.m.Y', $data['start_date'])->format('Y-m-d');
        $data['end_date'] = Carbon::createFromFormat('d.m.Y', $data['end_date'])->format('Y-m-d');

        $data['times'] = array_values(array_unique($data['times']));

        $treatment = \App\Models\Treatment::findOrFail($data['treatment_id']);
        $data['patient_id'] = $treatment->patient_id;
        $data['user_id'] = $treatment->patient->user_id ?? auth()->id();

        $plan = \App\Models\MedicationPlan::create($data);
        try {
            \App\Services\MedicationUsageGenerator::generate($plan);
            if ($plan->usages()->count() === 0) {
                $plan->delete();
                return redirect()->back()->withErrors(['times' => 'İlaç kullanım saatleriyle ilgili bir hata oluştu. Lütfen saatleri kontrol edin.']);
            }
        } catch (\Exception $e) {
            $plan->delete();
            return redirect()->back()->withErrors(['times' => 'İlaç kullanım saatleriyle ilgili bir hata oluştu: ' . $e->getMessage()]);
        }

        return redirect()->route('treatments.show', $treatment->id)
            ->with('success', 'İlaç planı başarıyla eklendi.');
    }

    public function edit(\App\Models\MedicationPlan $plan)
    {
        return view('medications.edit', [
            'plan' => $plan,
            'treatment' => $plan->treatment
        ]);
    }

    public function update(Request $request, \App\Models\MedicationPlan $plan)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:oral,topical,spray,vitamin',
            'dose' => 'required|string',
            'instructions' => 'nullable|string',
            'times' => 'required|array',
            'times.*' => 'required|date_format:H:i',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $data['start_date'] = Carbon::createFromFormat('d.m.Y', $data['start_date'])->format('Y-m-d');
        $data['end_date'] = Carbon::createFromFormat('d.m.Y', $data['end_date'])->format('Y-m-d');

        $data['times'] = array_values(array_unique($data['times']));

        $plan->update($data);

        // Akıllı şekilde usage kayıtlarını senkronize et
        \App\Services\MedicationUsageGenerator::sync($plan);

        return redirect()->route('medication-plans.edit', $plan)
                         ->with('success', 'İlaç planı güncellendi.');
    }

    public function destroy(\App\Models\MedicationPlan $plan)
    {
        $plan->delete();

        return redirect()->back()->with('success', 'İlaç planı silindi.');
    }

    public function create($treatmentId)
    {
        $treatment = \App\Models\Treatment::findOrFail($treatmentId);
        $patient = $treatment->patient;
        return view('medications.create', compact('treatment', 'patient'));
    }
} 