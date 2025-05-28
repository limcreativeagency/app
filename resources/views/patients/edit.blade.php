@extends('layouts.app')

@push('styles')
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
<style>
    .iti { width: 100%; } /* Ensure the plugin takes full width of the column */
    .tagify{
        --tag-bg: #0d6efd;
        --tag-text-color: white;
        --tag-remove-btn-color: white;
        border-color: #ced4da;
        border-radius: 0.375rem;
        min-height: calc(1.5em + 0.75rem + 2px); /* Match form-control height */
        padding: 0.375rem 0.75rem; /* Match form-control padding */
    }
    .tagify__input{
        margin: 0; /* Reset margin */
        padding: 0.375rem 0.75rem; /* Match form-control padding */
        line-height: 1.5; /* Match form-control line-height */
    }
    .tagify__tag{
        margin: 0.25rem; /* Add some margin around tags */
    }
    .form-group .tagify + .invalid-feedback, .form-group .tagify + .form-text{
        display: block; /* Ensure feedback/help text is visible */
    }
    /* Tab stilleri */
    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 1rem;
    }
    .nav-tabs .nav-link {
        margin-bottom: -2px;
        border: none;
        border-bottom: 2px solid transparent;
        border-radius: 0;
        color: #6c757d;
        padding: 1rem 1.5rem;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }
    .nav-tabs .nav-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
        isolation: isolate;
        color: #0d6efd;
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom: 2px solid #0d6efd;
        background-color: transparent;
    }
    .nav-tabs .nav-link i {
        margin-right: 8px;
    }
    .tab-content {
        padding: 20px 0;
    }
    .tab-pane {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
                </div>
            @endif
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">{{ __('patients.patient_details') }}</h5>
                    <div class="card-tools">
                        <a href="{{ route('patients.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('general.back') }}
                        </a>
                    </div>
                </div>
                <form id="patientEditForm" action="{{ route('patients.update', $patient) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Telefon için gizli alan -->
                    <input type="hidden" id="full_phone" name="phone" value="{{ old('phone', $patient->user->phone) }}">
                    <input type="hidden" id="country_code" name="country_code" value="{{ old('country_code', $patient->user->country_code) }}">

                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Tab Menüsü -->
                        <ul class="nav nav-tabs" id="patientTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                                    <i class="bi bi-person-circle"></i> Kişisel Bilgiler
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="health-tab" data-bs-toggle="tab" data-bs-target="#health" type="button" role="tab">
                                    <i class="bi bi-heart-pulse"></i> Sağlık Bilgileri
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="emergency-tab" data-bs-toggle="tab" data-bs-target="#emergency" type="button" role="tab">
                                    <i class="bi bi-people-fill"></i> Acil Durum
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes" type="button" role="tab">
                                    <i class="bi bi-journal-text"></i> Notlar
                                </button>
                            </li>
                        </ul>

                        <!-- Tab İçerikleri -->
                        <div class="tab-content" id="patientTabsContent">
                            <!-- Kişisel Bilgiler -->
                            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="name" class="form-label">{{ __('patients.name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $patient->user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">{{ __('patients.email') }} <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $patient->user->email) }}" required>
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">{{ __('patients.phone') }}</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $patient->user->phone) }}">
                                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="identity_number" class="form-label">{{ __('patients.identity_number') }}</label>
                                        <input type="text" class="form-control @error('identity_number') is-invalid @enderror" id="identity_number" name="identity_number" value="{{ old('identity_number', $patient->identity_number) }}" placeholder="{{ __('patients.identity_number_placeholder') }}">
                                        @error('identity_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="birth_date" class="form-label">{{ __('patients.birth_date') }}</label>
                                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date', $patient->birth_date ? $patient->birth_date->format('Y-m-d') : '') }}">
                                        @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="gender" class="form-label">{{ __('patients.gender') }}</label>
                                        <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                            <option value="">{{ __('general.select') }}</option>
                                            <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>{{ __('patients.gender_male') }}</option>
                                            <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>{{ __('patients.gender_female') }}</option>
                                            <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>{{ __('patients.gender_other') }}</option>
                                        </select>
                                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="marital_status" class="form-label">{{ __('patients.marital_status') }}</label>
                                        <select class="form-control @error('marital_status') is-invalid @enderror" id="marital_status" name="marital_status">
                                            <option value="">{{ __('general.select') }}</option>
                                            <option value="single" {{ old('marital_status', $patient->marital_status) == 'single' ? 'selected' : '' }}>{{ __('patients.single') }}</option>
                                            <option value="married" {{ old('marital_status', $patient->marital_status) == 'married' ? 'selected' : '' }}>{{ __('patients.married') }}</option>
                                            <option value="divorced" {{ old('marital_status', $patient->marital_status) == 'divorced' ? 'selected' : '' }}>{{ __('patients.divorced') }}</option>
                                            <option value="widowed" {{ old('marital_status', $patient->marital_status) == 'widowed' ? 'selected' : '' }}>{{ __('patients.widowed') }}</option>
                                        </select>
                                        @error('marital_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="occupation" class="form-label">{{ __('patients.occupation') }}</label>
                                        <input type="text" class="form-control @error('occupation') is-invalid @enderror" id="occupation" name="occupation" value="{{ old('occupation', $patient->occupation) }}" placeholder="{{ __('patients.occupation_placeholder') }}">
                                        @error('occupation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label for="address" class="form-label">{{ __('patients.address') }}</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $patient->address) }}</textarea>
                                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="city" class="form-label">{{ __('patients.city') }}</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $patient->city) }}">
                                        @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="country" class="form-label">{{ __('patients.country') }}</label>
                                        <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country', $patient->country) }}">
                                        @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="postal_code" class="form-label">{{ __('patients.postal_code') }}</label>
                                        <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code', $patient->postal_code) }}">
                                        @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Sağlık Bilgileri -->
                            <div class="tab-pane fade" id="health" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('patients.blood_type') }}</label>
                                        <select name="blood_type" class="form-select @error('blood_type') is-invalid @enderror">
                                            <option value="">{{ __('patients.blood_type_placeholder') }}</option>
                                            <option value="A+" {{ old('blood_type', $patient->blood_type) == 'A+' ? 'selected' : '' }}>A Rh+</option>
                                            <option value="A-" {{ old('blood_type', $patient->blood_type) == 'A-' ? 'selected' : '' }}>A Rh-</option>
                                            <option value="B+" {{ old('blood_type', $patient->blood_type) == 'B+' ? 'selected' : '' }}>B Rh+</option>
                                            <option value="B-" {{ old('blood_type', $patient->blood_type) == 'B-' ? 'selected' : '' }}>B Rh-</option>
                                            <option value="AB+" {{ old('blood_type', $patient->blood_type) == 'AB+' ? 'selected' : '' }}>AB Rh+</option>
                                            <option value="AB-" {{ old('blood_type', $patient->blood_type) == 'AB-' ? 'selected' : '' }}>AB Rh-</option>
                                            <option value="0+" {{ old('blood_type', $patient->blood_type) == '0+' ? 'selected' : '' }}>0 Rh+</option>
                                            <option value="0-" {{ old('blood_type', $patient->blood_type) == '0-' ? 'selected' : '' }}>0 Rh-</option>
                                        </select>
                                        @error('blood_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('patients.height') }}</label>
                                        <div class="input-group">
                                            <input type="number" name="height" class="form-control @error('height') is-invalid @enderror" placeholder="{{ __('patients.height_placeholder') }}" value="{{ old('height', $patient->height) }}">
                                            <span class="input-group-text">cm</span>
                                        </div>
                                        @error('height') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('patients.weight') }}</label>
                                        <div class="input-group">
                                            <input type="number" name="weight" class="form-control @error('weight') is-invalid @enderror" placeholder="{{ __('patients.weight_placeholder') }}" value="{{ old('weight', $patient->weight) }}">
                                            <span class="input-group-text">kg</span>
                                        </div>
                                        @error('weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ __('patients.medical_history') }}</label>
                                        <textarea name="medical_history" class="form-control @error('medical_history') is-invalid @enderror" rows="3" placeholder="{{ __('patients.medical_history_placeholder') }}">{{ old('medical_history', $patient->medical_history) }}</textarea>
                                        @error('medical_history') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="smoking_status" class="form-label">{{ __('patients.smoking_status') }}</label>
                                        <select class="form-select @error('smoking_status') is-invalid @enderror" id="smoking_status" name="smoking_status">
                                            <option value="">{{ __('general.select') }}</option>
                                            <option value="never" {{ old('smoking_status', $patient->smoking_status) == 'never' ? 'selected' : '' }}>{{ __('patients.never_smoked') }}</option>
                                            <option value="former" {{ old('smoking_status', $patient->smoking_status) == 'former' ? 'selected' : '' }}>{{ __('patients.former_smoker') }}</option>
                                            <option value="current" {{ old('smoking_status', $patient->smoking_status) == 'current' ? 'selected' : '' }}>{{ __('patients.current_smoker') }}</option>
                                        </select>
                                        @error('smoking_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="alcohol_consumption" class="form-label">{{ __('patients.alcohol_consumption') }}</label>
                                        <select class="form-select @error('alcohol_consumption') is-invalid @enderror" id="alcohol_consumption" name="alcohol_consumption">
                                            <option value="">{{ __('general.select') }}</option>
                                            <option value="never" {{ old('alcohol_consumption', $patient->alcohol_consumption) == 'never' ? 'selected' : '' }}>{{ __('patients.never_drinks') }}</option>
                                            <option value="occasional" {{ old('alcohol_consumption', $patient->alcohol_consumption) == 'occasional' ? 'selected' : '' }}>{{ __('patients.occasional_drinker') }}</option>
                                            <option value="regular" {{ old('alcohol_consumption', $patient->alcohol_consumption) == 'regular' ? 'selected' : '' }}>{{ __('patients.regular_drinker') }}</option>
                                            <option value="former" {{ old('alcohol_consumption', $patient->alcohol_consumption) == 'former' ? 'selected' : '' }}>{{ __('patients.former_drinker') }}</option>
                                        </select>
                                        @error('alcohol_consumption') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exercise_status" class="form-label">{{ __('patients.exercise_status') }}</label>
                                        <select class="form-select @error('exercise_status') is-invalid @enderror" id="exercise_status" name="exercise_status">
                                            <option value="">{{ __('general.select') }}</option>
                                            <option value="none" {{ old('exercise_status', $patient->exercise_status) == 'none' ? 'selected' : '' }}>{{ __('patients.no_exercise') }}</option>
                                            <option value="occasional" {{ old('exercise_status', $patient->exercise_status) == 'occasional' ? 'selected' : '' }}>{{ __('patients.occasional_exercise') }}</option>
                                            <option value="regular" {{ old('exercise_status', $patient->exercise_status) == 'regular' ? 'selected' : '' }}>{{ __('patients.regular_exercise') }}</option>
                                        </select>
                                        @error('exercise_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="dietary_habits" class="form-label">{{ __('patients.dietary_habits') }}</label>
                                        <textarea class="form-control @error('dietary_habits') is-invalid @enderror" id="dietary_habits" name="dietary_habits" rows="2" placeholder="{{ __('patients.dietary_habits_placeholder') }}">{{ old('dietary_habits', $patient->dietary_habits) }}</textarea>
                                        @error('dietary_habits') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label for="allergies" class="form-label">{{ __('patients.allergies') }}</label>
                                        <input type="text" class="form-control tagify-input @error('allergies') is-invalid @enderror @error('allergies.*') is-invalid @enderror" id="allergies" name="allergies" value="{{ old('allergies', is_array($patient->allergies) ? implode(',', array_map(function($item) { return is_array($item) ? $item['value'] : $item; }, $patient->allergies)) : ($patient->allergies ?? '')) }}" placeholder="{{ __('patients.allergies_placeholder') }}">
                                        @error('allergies') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        @error('allergies.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label for="chronic_diseases" class="form-label">{{ __('patients.chronic_diseases') }}</label>
                                        <input type="text" class="form-control tagify-input @error('chronic_diseases') is-invalid @enderror @error('chronic_diseases.*') is-invalid @enderror" id="chronic_diseases" name="chronic_diseases" value="{{ old('chronic_diseases', is_array($patient->chronic_diseases) ? implode(',', array_map(function($item) { return is_array($item) ? $item['value'] : $item; }, $patient->chronic_diseases)) : ($patient->chronic_diseases ?? '')) }}" placeholder="{{ __('patients.chronic_diseases_placeholder') }}">
                                        @error('chronic_diseases') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        @error('chronic_diseases.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label for="medications_used" class="form-label">{{ __('patients.medications_used') }}</label>
                                        <input type="text" class="form-control tagify-input @error('medications_used') is-invalid @enderror @error('medications_used.*') is-invalid @enderror" id="medications_used" name="medications_used" value="{{ old('medications_used', is_array($patient->medications_used) ? implode(',', array_map(function($item) { return is_array($item) ? $item['value'] : $item; }, $patient->medications_used)) : ($patient->medications_used ?? '')) }}" placeholder="{{ __('patients.medications_used_placeholder') }}">
                                        @error('medications_used') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        @error('medications_used.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Emergency Contacts Tab -->
                            <div class="tab-pane fade" id="emergency" role="tabpanel" aria-labelledby="emergency-tab">
                                <div id="emergency-contacts-wrapper">
                                    @if($patient->emergencyContacts && $patient->emergencyContacts->count() > 0)
                                        @foreach($patient->emergencyContacts as $index => $contact)
                                            <div class="row g-3 mb-2 emergency-contact-row">
                                                <div class="col-md-4">
                                                    <label class="form-label">{{ __('patients.contact_name') }}</label>
                                                    <input type="text" name="emergency_contacts[{{ $index }}][name]" 
                                                           class="form-control @error('emergency_contacts.'.$index.'.name') is-invalid @enderror" 
                                                           placeholder="{{ __('patients.contact_name_placeholder') }}" 
                                                           value="{{ old('emergency_contacts.'.$index.'.name', $contact->name) }}">
                                                    @error('emergency_contacts.'.$index.'.name') 
                                                        <div class="invalid-feedback d-block">{{ $message }}</div> 
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">{{ __('patients.contact_phone') }}</label>
                                                    <input type="text" name="emergency_contacts[{{ $index }}][phone]" 
                                                           class="form-control @error('emergency_contacts.'.$index.'.phone') is-invalid @enderror" 
                                                           placeholder="{{ __('patients.contact_phone_placeholder') }}" 
                                                           value="{{ old('emergency_contacts.'.$index.'.phone', $contact->phone) }}">
                                                    @error('emergency_contacts.'.$index.'.phone') 
                                                        <div class="invalid-feedback d-block">{{ $message }}</div> 
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">{{ __('patients.contact_relation') }}</label>
                                                    <input type="text" name="emergency_contacts[{{ $index }}][relation]" 
                                                           class="form-control @error('emergency_contacts.'.$index.'.relation') is-invalid @enderror" 
                                                           placeholder="{{ __('patients.contact_relation_placeholder') }}" 
                                                           value="{{ old('emergency_contacts.'.$index.'.relation', $contact->relation) }}">
                                                    @error('emergency_contacts.'.$index.'.relation') 
                                                        <div class="invalid-feedback d-block">{{ $message }}</div> 
                                                    @enderror
                                                </div>
                                                <div class="col-md-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger btn-sm remove-emergency-contact">&times;</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row g-3 mb-2 emergency-contact-row">
                                            <div class="col-md-4">
                                                <label class="form-label">{{ __('patients.contact_name') }}</label>
                                                <input type="text" name="emergency_contacts[0][name]" 
                                                       class="form-control @error('emergency_contacts.0.name') is-invalid @enderror" 
                                                       placeholder="{{ __('patients.contact_name_placeholder') }}" 
                                                       value="{{ old('emergency_contacts.0.name') }}">
                                                @error('emergency_contacts.0.name') 
                                                    <div class="invalid-feedback d-block">{{ $message }}</div> 
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">{{ __('patients.contact_phone') }}</label>
                                                <input type="text" name="emergency_contacts[0][phone]" 
                                                       class="form-control @error('emergency_contacts.0.phone') is-invalid @enderror" 
                                                       placeholder="{{ __('patients.contact_phone_placeholder') }}" 
                                                       value="{{ old('emergency_contacts.0.phone') }}">
                                                @error('emergency_contacts.0.phone') 
                                                    <div class="invalid-feedback d-block">{{ $message }}</div> 
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">{{ __('patients.contact_relation') }}</label>
                                                <input type="text" name="emergency_contacts[0][relation]" 
                                                       class="form-control @error('emergency_contacts.0.relation') is-invalid @enderror" 
                                                       placeholder="{{ __('patients.contact_relation_placeholder') }}" 
                                                       value="{{ old('emergency_contacts.0.relation') }}">
                                                @error('emergency_contacts.0.relation') 
                                                    <div class="invalid-feedback d-block">{{ $message }}</div> 
                                                @enderror
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-emergency-contact" style="display:none;">&times;</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm mt-3" id="add-emergency-contact">
                                    <i class="bi bi-plus"></i> {{ __('patients.add_emergency_contact') }}
                                </button>
                            </div>

                            <!-- Notes Tab -->
                            <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="notes" class="visually-hidden">{{ __('patients.notes') }}</label>
                                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                      id="notes" name="notes" rows="5" 
                                                      placeholder="{{ __('patients.notes_placeholder') }}">{{ old('notes', $patient->notes) }}</textarea>
                                            @error('notes') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> {{ __('general.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}

<!-- Plugin JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Otomatik kaybolacak alert için
        const alertElement = document.querySelector('.alert-success');
        if (alertElement) {
            setTimeout(function() {
                const alert = new bootstrap.Alert(alertElement);
                alert.close();
            }, 3000);
        }

        // Initialize phone input
        const phoneInputField = document.querySelector("#phone");
        let iti = null;

        if (phoneInputField) {
            iti = window.intlTelInput(phoneInputField, {
                initialCountry: "tr",
                preferredCountries: ['tr'],
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
            });

            // Set initial phone number
            const storedPhone = document.querySelector("#full_phone").value;
            if (storedPhone) {
                iti.setNumber(storedPhone);
            }

            // Phone number change handler
            phoneInputField.addEventListener('change', function() {
                if (this.value) {
                    const countryData = iti.getSelectedCountryData();
                    document.querySelector("#country_code").value = countryData.dialCode;
                    document.querySelector("#full_phone").value = iti.getNumber();
                }
            });

            // Country change handler
            phoneInputField.addEventListener("countrychange", function() {
                const countryData = iti.getSelectedCountryData();
                document.querySelector("#country_code").value = countryData.dialCode;
                document.querySelector("#full_phone").value = iti.getNumber();
            });
        }

        // Form submit handler
        const form = document.getElementById('patientEditForm');
        form.addEventListener('submit', function(e) {
            if (phoneInputField && phoneInputField.value) {
                const phoneNumber = iti.getNumber();
                if (phoneNumber && phoneNumber !== '+undefined' && phoneNumber !== 'undefined') {
                    document.querySelector("#full_phone").value = phoneNumber;
                    document.querySelector("#country_code").value = iti.getSelectedCountryData().dialCode;
                } else {
                    document.querySelector("#full_phone").value = '';
                    document.querySelector("#country_code").value = '';
                }
            } else {
                document.querySelector("#full_phone").value = '';
                document.querySelector("#country_code").value = '';
            }
        });

        // Initialize Bootstrap tabs
        const tabElements = document.querySelectorAll('#patientTabs button[data-bs-toggle="tab"]');
        tabElements.forEach(function(tabElement) {
            const tab = new bootstrap.Tab(tabElement);
            tabElement.addEventListener('click', function(event) {
                event.preventDefault();
                tab.show();
            });
        });

        // Restore active tab
        const activeTabId = localStorage.getItem('activePatientTab');
        if (activeTabId) {
            const activeTabElement = document.querySelector(activeTabId);
            if (activeTabElement) {
                const activeTab = new bootstrap.Tab(activeTabElement);
                activeTab.show();
            }
        }

        // Store active tab
        tabElements.forEach(function(tabElement) {
            tabElement.addEventListener('shown.bs.tab', function(event) {
                localStorage.setItem('activePatientTab', '#' + event.target.id);
            });
        });

        // TC Kimlik input mask
        const identityInput = document.getElementById('identity_number');
        if (identityInput) {
            identityInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) value = value.slice(0, 11);
                e.target.value = value;
            });
        }

        // Initialize Tagify
        ['allergies', 'chronic_diseases', 'medications_used'].forEach(function(fieldName) {
            const input = document.getElementById(fieldName);
            if (input) {
                new Tagify(input, {});
            }
        });

        // Emergency contacts handling
        let contactIndex = {{ $patient->emergencyContacts->count() > 0 ? $patient->emergencyContacts->count() : 1 }};
        const addContactButton = document.getElementById('add-emergency-contact');
        const contactsWrapper = document.getElementById('emergency-contacts-wrapper');

        if (addContactButton && contactsWrapper) {
            addContactButton.addEventListener('click', function() {
                const template = `
                    <div class="row g-3 mb-2 emergency-contact-row">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('patients.contact_name') }}</label>
                            <input type="text" name="emergency_contacts[${contactIndex}][name]" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('patients.contact_phone') }}</label>
                            <input type="text" name="emergency_contacts[${contactIndex}][phone]" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('patients.contact_relation') }}</label>
                            <input type="text" name="emergency_contacts[${contactIndex}][relation]" class="form-control" required>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-emergency-contact">&times;</button>
                        </div>
                    </div>`;
                contactsWrapper.insertAdjacentHTML('beforeend', template);
                contactIndex++;
                updateRemoveButtons();
            });

            // Remove contact handler
            contactsWrapper.addEventListener('click', function(e) {
                if (e.target.matches('.remove-emergency-contact')) {
                    e.target.closest('.emergency-contact-row').remove();
                    updateRemoveButtons();
                }
            });

            function updateRemoveButtons() {
                const removeButtons = document.querySelectorAll('.remove-emergency-contact');
                const rows = document.querySelectorAll('.emergency-contact-row');
                removeButtons.forEach(button => {
                    button.style.display = rows.length === 1 ? 'none' : 'block';
                });
            }

            updateRemoveButtons();
        }
    });
</script>
@endpush 