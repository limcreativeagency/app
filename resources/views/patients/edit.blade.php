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
                    <h3 class="card-title">{{ __('patients.patient_details') }}</h3>
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
                                           value="{{ old('identity_number', $patient->identity_number) }}"
                                           placeholder="{{ __('patients.identity_number_placeholder') }}">
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
                                        <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>{{ __('patients.gender_male') }}</option>
                                        <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>{{ __('patients.gender_female') }}</option>
                                        <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>{{ __('patients.gender_other') }}</option>
                                    </select>
                                    @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="marital_status">{{ __('patients.marital_status') }}</label>
                                    <select class="form-control @error('marital_status') is-invalid @enderror" 
                                            id="marital_status" name="marital_status">
                                        <option value="">{{ __('general.select') }}</option>
                                        <option value="single" {{ old('marital_status', $patient->marital_status) == 'single' ? 'selected' : '' }}>{{ __('patients.single') }}</option>
                                        <option value="married" {{ old('marital_status', $patient->marital_status) == 'married' ? 'selected' : '' }}>{{ __('patients.married') }}</option>
                                        <option value="divorced" {{ old('marital_status', $patient->marital_status) == 'divorced' ? 'selected' : '' }}>{{ __('patients.divorced') }}</option>
                                        <option value="widowed" {{ old('marital_status', $patient->marital_status) == 'widowed' ? 'selected' : '' }}>{{ __('patients.widowed') }}</option>
                                    </select>
                                    @error('marital_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="occupation">{{ __('patients.occupation') }}</label>
                                    <input type="text" class="form-control @error('occupation') is-invalid @enderror" 
                                           id="occupation" name="occupation" 
                                           value="{{ old('occupation', $patient->occupation) }}"
                                           placeholder="{{ __('patients.occupation_placeholder') }}">
                                    @error('occupation') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="height">{{ __('patients.height') }}</label>
                                            <input type="number" step="0.01" class="form-control @error('height') is-invalid @enderror" 
                                                   id="height" name="height" 
                                                   value="{{ old('height', $patient->height) }}"
                                                   placeholder="{{ __('patients.height_placeholder') }}">
                                            @error('height') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="weight">{{ __('patients.weight') }}</label>
                                            <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror" 
                                                   id="weight" name="weight" 
                                                   value="{{ old('weight', $patient->weight) }}"
                                                   placeholder="{{ __('patients.weight_placeholder') }}">
                                            @error('weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="smoking_status">{{ __('patients.smoking_status') }}</label>
                                    <select class="form-control @error('smoking_status') is-invalid @enderror" 
                                            id="smoking_status" name="smoking_status">
                                        <option value="">{{ __('general.select') }}</option>
                                        <option value="never" {{ old('smoking_status', $patient->smoking_status) == 'never' ? 'selected' : '' }}>{{ __('patients.never_smoked') }}</option>
                                        <option value="former" {{ old('smoking_status', $patient->smoking_status) == 'former' ? 'selected' : '' }}>{{ __('patients.former_smoker') }}</option>
                                        <option value="current" {{ old('smoking_status', $patient->smoking_status) == 'current' ? 'selected' : '' }}>{{ __('patients.current_smoker') }}</option>
                                    </select>
                                    @error('smoking_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="alcohol_consumption">{{ __('patients.alcohol_consumption') }}</label>
                                    <select class="form-control @error('alcohol_consumption') is-invalid @enderror" 
                                            id="alcohol_consumption" name="alcohol_consumption">
                                        <option value="">{{ __('general.select') }}</option>
                                        <option value="never" {{ old('alcohol_consumption', $patient->alcohol_consumption) == 'never' ? 'selected' : '' }}>{{ __('patients.never_drinks') }}</option>
                                        <option value="occasional" {{ old('alcohol_consumption', $patient->alcohol_consumption) == 'occasional' ? 'selected' : '' }}>{{ __('patients.occasional_drinker') }}</option>
                                        <option value="regular" {{ old('alcohol_consumption', $patient->alcohol_consumption) == 'regular' ? 'selected' : '' }}>{{ __('patients.regular_drinker') }}</option>
                                        <option value="former" {{ old('alcohol_consumption', $patient->alcohol_consumption) == 'former' ? 'selected' : '' }}>{{ __('patients.former_drinker') }}</option>
                                    </select>
                                    @error('alcohol_consumption') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exercise_status">{{ __('patients.exercise_status') }}</label>
                                    <select class="form-control @error('exercise_status') is-invalid @enderror" 
                                            id="exercise_status" name="exercise_status">
                                        <option value="">{{ __('general.select') }}</option>
                                        <option value="none" {{ old('exercise_status', $patient->exercise_status) == 'none' ? 'selected' : '' }}>{{ __('patients.no_exercise') }}</option>
                                        <option value="occasional" {{ old('exercise_status', $patient->exercise_status) == 'occasional' ? 'selected' : '' }}>{{ __('patients.occasional_exercise') }}</option>
                                        <option value="regular" {{ old('exercise_status', $patient->exercise_status) == 'regular' ? 'selected' : '' }}>{{ __('patients.regular_exercise') }}</option>
                                    </select>
                                    @error('exercise_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="dietary_habits">{{ __('patients.dietary_habits') }}</label>
                                    <textarea class="form-control @error('dietary_habits') is-invalid @enderror" 
                                              id="dietary_habits" name="dietary_habits" rows="2"
                                              placeholder="{{ __('patients.dietary_habits_placeholder') }}">{{ old('dietary_habits', $patient->dietary_habits) }}</textarea>
                                    @error('dietary_habits') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="allergies" class="form-label">{{ __('patients.allergies') }}</label>
                                    <input type="text" class="form-control tagify-input @error('allergies') is-invalid @enderror @error('allergies.*') is-invalid @enderror" 
                                           id="allergies" 
                                           name="allergies" 
                                           value="{{ old('allergies', is_array($patient->allergies) ? implode(',', array_map(function($item) { return is_array($item) ? $item['value'] : $item; }, $patient->allergies)) : ($patient->allergies ?? '')) }}"
                                           placeholder="{{ __('patients.allergies_placeholder') }}">
                                    @error('allergies') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    @error('allergies.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="chronic_diseases" class="form-label">{{ __('patients.chronic_diseases') }}</label>
                                    <input type="text" class="form-control tagify-input @error('chronic_diseases') is-invalid @enderror @error('chronic_diseases.*') is-invalid @enderror" 
                                           id="chronic_diseases" 
                                           name="chronic_diseases" 
                                           value="{{ old('chronic_diseases', is_array($patient->chronic_diseases) ? implode(',', array_map(function($item) { return is_array($item) ? $item['value'] : $item; }, $patient->chronic_diseases)) : ($patient->chronic_diseases ?? '')) }}"
                                           placeholder="{{ __('patients.chronic_diseases_placeholder') }}">
                                    @error('chronic_diseases') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    @error('chronic_diseases.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="medications_used">{{ __('patients.medications_used') }}</label>
                                    <input type="text" class="form-control tagify-input @error('medications_used') is-invalid @enderror"
                                        id="medications_used" name="medications_used"
                                        value="{{ old('medications_used', $patient->medications_used ? (is_array($patient->medications_used) ? implode(',', $patient->medications_used) : $patient->medications_used) : '') }}"
                                        placeholder="{{ __('patients.medications_used_placeholder') }}">
                                    @error('medications_used') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <h4 class="mt-4">{{ __('patients.notes') }}</h4>
                                <div class="form-group">
                                    <label for="notes" class="visually-hidden">{{ __('patients.notes') }}</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3" placeholder="Hasta ile ilgili genel notlar...">{{ old('notes', $patient->notes) }}</textarea>
                                    @error('notes') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <h4 class="mt-4">{{ __('patients.section_emergency') }}</h4>
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
                                <button type="button" class="btn btn-outline-primary btn-sm mb-3" id="add-emergency-contact">
                                    <i class="bi bi-plus"></i> {{ __('patients.add_emergency_contact') }}
                                </button>
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

        // Emergency Contacts
        let contactIndex = {{ $patient->emergencyContacts->count() > 0 ? $patient->emergencyContacts->count() : 1 }};
        var emergencyLabels = {
            name: "{{ __('patients.contact_name') }}",
            phone: "{{ __('patients.contact_phone') }}",
            relation: "{{ __('patients.contact_relation') }}"
        };
        var emergencyPlaceholders = {
            name: "{{ __('patients.contact_name_placeholder') }}",
            phone: "{{ __('patients.contact_phone_placeholder') }}",
            relation: "{{ __('patients.contact_relation_placeholder') }}"
        };

        $('#add-emergency-contact').on('click', function() {
            let row = `<div class="row g-3 mb-2 emergency-contact-row">
                <div class="col-md-4">
                    <label class="form-label">${emergencyLabels.name}</label>
                    <input type="text" name="emergency_contacts[${contactIndex}][name]" class="form-control" 
                           placeholder="${emergencyPlaceholders.name}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">${emergencyLabels.phone}</label>
                    <input type="text" name="emergency_contacts[${contactIndex}][phone]" class="form-control" 
                           placeholder="${emergencyPlaceholders.phone}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">${emergencyLabels.relation}</label>
                    <input type="text" name="emergency_contacts[${contactIndex}][relation]" class="form-control" 
                           placeholder="${emergencyPlaceholders.relation}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-emergency-contact">&times;</button>
                </div>
            </div>`;
            $('#emergency-contacts-wrapper').append(row);
            contactIndex++;
            updateRemoveButtons();
        });

        $(document).on('click', '.remove-emergency-contact', function() {
            $(this).closest('.emergency-contact-row').remove();
            updateRemoveButtons();
        });

        function updateRemoveButtons() {
            $('.remove-emergency-contact').show();
            if ($('.emergency-contact-row').length === 1) {
                $('.remove-emergency-contact').hide();
            }
        }
        updateRemoveButtons();
    });
</script>
@endpush 