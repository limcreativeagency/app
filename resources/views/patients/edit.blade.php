@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/25.3.1/build/css/intlTelInput.css">
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
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('patients.edit_patient_title', ['name' => $patient->user->name]) }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('patients.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('general.back') }}
                        </a>
                    </div>
                </div>
                <form action="{{ route('patients.update', $patient) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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

                        <div class="row">
                            <div class="col-md-6">
                                <h4>{{ __('patients.section_personal') }}</h4>
                                <div class="form-group">
                                    <label for="name">{{ __('patients.name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $patient->user->name) }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">{{ __('patients.email') }} <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $patient->user->email) }}" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone">{{ __('patients.phone') }}</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $patient->user->phone) }}">
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="identity_number">{{ __('patients.identity_number') }}</label>
                                    <input type="text" class="form-control @error('identity_number') is-invalid @enderror" 
                                           id="identity_number" name="identity_number" 
                                           value="{{ old('identity_number', $patient->identity_number) }}">
                                    @error('identity_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="birth_date">{{ __('patients.birth_date') }}</label>
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                           id="birth_date" name="birth_date" 
                                           value="{{ old('birth_date', $patient->birth_date ? $patient->birth_date->format('Y-m-d') : '') }}">
                                    @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="gender">{{ __('patients.gender') }}</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" 
                                            id="gender" name="gender">
                                        <option value="">{{ __('general.select') }}</option>
                                        <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>{{ __('general.male') }}</option>
                                        <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>{{ __('general.female') }}</option>
                                        <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>{{ __('general.other') }}</option>
                                    </select>
                                    @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="profile_photo">{{ __('patients.profile_photo') }}</label>
                                    @if($patient->profile_photo)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($patient->profile_photo) }}" 
                                                 alt="{{ $patient->user->name }}" 
                                                 class="img-thumbnail" 
                                                 style="max-width: 150px; max-height: 150px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control-file @error('profile_photo') is-invalid @enderror" 
                                           id="profile_photo" name="profile_photo">
                                    <small class="form-text text-muted">{{ __('general.photo_update_note') }}</small>
                                    @error('profile_photo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h4>{{ __('patients.section_address') }}</h4>
                                <div class="form-group">
                                    <label for="address">{{ __('patients.address') }}</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3">{{ old('address', $patient->address) }}</textarea>
                                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="city">{{ __('patients.city') }}</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           id="city" name="city" value="{{ old('city', $patient->city) }}">
                                    @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="country">{{ __('patients.country') }}</label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                           id="country" name="country" value="{{ old('country', $patient->country) }}">
                                    @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="postal_code">{{ __('patients.postal_code') }}</label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                           id="postal_code" name="postal_code" value="{{ old('postal_code', $patient->postal_code) }}">
                                    @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <h4 class="mt-4">{{ __('patients.section_health') }}</h4>
                                <div class="form-group">
                                    <label for="medical_history">{{ __('patients.medical_history') }}</label>
                                    <textarea class="form-control @error('medical_history') is-invalid @enderror" 
                                              id="medical_history" name="medical_history" rows="3">{{ old('medical_history', $patient->medical_history) }}</textarea>
                                    @error('medical_history') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="blood_type">{{ __('patients.blood_type') }}</label>
                                    <input type="text" class="form-control @error('blood_type') is-invalid @enderror" 
                                           id="blood_type" name="blood_type" value="{{ old('blood_type', $patient->blood_type) }}">
                                    @error('blood_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="allergies">{{ __('patients.allergies') }}</label>
                                    <input type="text" class="form-control tagify-input @error('allergies') is-invalid @enderror @error('allergies.*') is-invalid @enderror" 
                                           id="allergies" name="allergies" 
                                           value="{{ old('allergies') ? (is_array(old('allergies')) ? implode(',', old('allergies')) : old('allergies')) : (is_array($patient->allergies) ? implode(',', $patient->allergies) : $patient->allergies) }}">
                                    @error('allergies') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    @error('allergies.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="chronic_diseases">{{ __('patients.chronic_diseases') }}</label>
                                    <input type="text" class="form-control tagify-input @error('chronic_diseases') is-invalid @enderror @error('chronic_diseases.*') is-invalid @enderror" 
                                           id="chronic_diseases" name="chronic_diseases" 
                                           value="{{ old('chronic_diseases') ? (is_array(old('chronic_diseases')) ? implode(',', old('chronic_diseases')) : old('chronic_diseases')) : (is_array($patient->chronic_diseases) ? implode(',', $patient->chronic_diseases) : $patient->chronic_diseases) }}">
                                    @error('chronic_diseases') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    @error('chronic_diseases.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="medications_used">{{ __('patients.medications_used') }}</label>
                                    <input type="text" class="form-control tagify-input @error('medications_used') is-invalid @enderror @error('medications_used.*') is-invalid @enderror" 
                                           id="medications_used" name="medications_used" 
                                           value="{{ old('medications_used') ? (is_array(old('medications_used')) ? implode(',', old('medications_used')) : old('medications_used')) : (is_array($patient->medications_used) ? implode(',', $patient->medications_used) : $patient->medications_used) }}">
                                    @error('medications_used') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    @error('medications_used.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                
                                <h4 class="mt-4">{{ __('patients.notes') }}</h4>
                                <div class="form-group">
                                    <label for="notes" class="visually-hidden">{{ __('patients.notes') }}</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3" placeholder="Hasta ile ilgili genel notlar...">{{ old('notes', $patient->notes) }}</textarea>
                                    @error('notes') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('general.update') }}
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
{{-- Add jQuery Mask Plugin AFTER jQuery --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/25.3.1/build/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/25.3.1/build/js/utils.js"></script> {{-- This is the utils.js causing the 'export' error --}}
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>

<script>
    $(document).ready(function() {
        const phoneInput = document.querySelector("#phone");
        if(phoneInput){
            const itiPhone = window.intlTelInput(phoneInput, {
                initialCountry: "auto",
                geoIpLookup: function(callback) {
                    $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "tr";
                        callback(countryCode);
                    });
                },
                nationalMode: false,
                preferredCountries: ['tr', 'us', 'gb', 'de'],
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/25.3.1/build/js/utils.js" // Path to utils.js
            });
            if (phoneInput.value) {
                itiPhone.setNumber(phoneInput.value);
            }
            $('form').submit(function() { 
                if (phoneInput.value.trim()) {
                    phoneInput.value = itiPhone.getNumber().trim();
                }
            });
        }

        // Initialize jQuery Mask Plugin if it's loaded
        if (typeof $.fn.mask === 'function') { 
            $('#identity_number').mask('99999999999');
        } else {
            console.warn('jQuery Mask Plugin is not loaded.');
        }

        // Initialize Tagify for taggable inputs
        ['allergies', 'chronic_diseases', 'medications_used'].forEach(function(fieldName) {
            var input = document.querySelector('#' + fieldName);
            if (input) {
                new Tagify(input, {
                    // Tagify options can be customized here
                });
            }
        });

        // Regarding utils.js 'export' error (see comments in create.blade.php)
    });
</script>
@endpush 