<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentController extends Controller
{
    public function index()
    {
        $treatments = Treatment::with(['patient', 'user'])
            ->when(Auth::user()->role === 'patient', function ($query) {
                return $query->where('patient_id', Auth::user()->patient->id);
            })
            ->latest()
            ->paginate(10);

        return view('treatments.index', compact('treatments'));
    }

    public function create()
    {
        $presetPatient = null;
        if (request()->has('patient_id')) {
            $presetPatient = \App\Models\Patient::find(request()->patient_id);
        }
        $patients = \App\Models\Patient::all();
        return view('treatments.create', compact('presetPatient', 'patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
            'treatment_type' => 'required|string|max:255',
            'treatment_area' => 'required|array',
            'graft_count' => 'required|string',
            'operation_date' => 'required|date',
        ]);

        $validated['treatment_area'] = json_encode($validated['treatment_area']);

        $treatment = Treatment::create($validated);

        return redirect()->route('treatments.show', $treatment)
            ->with('success', 'Tedavi başarıyla oluşturuldu.');
    }

    public function show(Treatment $treatment)
    {
        $treatment->load(['patient', 'user', 'photos', 'stages', 'medicationPlans']);
        return view('treatments.show', compact('treatment'));
    }

    public function edit(Treatment $treatment)
    {
        $patients = Patient::all();
        $doctors = \App\Models\User::whereHas('role', function($q){ $q->where('slug', 'doctor'); })->get();
        return view('treatments.edit', compact('treatment', 'patients', 'doctors'));
    }

    public function update(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
            'treatment_type' => 'required|string|max:255',
            'treatment_area' => 'required|array',
            'graft_count' => 'required|string',
            'operation_date' => 'required|date',
        ]);

        $validated['treatment_area'] = json_encode($validated['treatment_area']);

        $treatment->update($validated);

        return redirect()->route('treatments.show', $treatment)
            ->with('success', 'Tedavi başarıyla güncellendi.');
    }

    public function destroy(Treatment $treatment)
    {
        $treatment->delete();
        return redirect()->route('treatments.index')
            ->with('success', 'Tedavi başarıyla silindi.');
    }
} 