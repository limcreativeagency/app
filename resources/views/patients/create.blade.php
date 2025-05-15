@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/css/intlTelInput.min.css">
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
<style>
    .iti { width: 100% !important; }
    .iti input { width: 100% !important; min-width: 0 !important; box-sizing: border-box; }
    .tagify{
        --tag-bg: #0d6efd; /* Bootstrap primary blue for tag background */
        --tag-text-color: white;
        --tag-remove-btn-color: white;
        border-color: #ced4da; /* Default Bootstrap border color */
        border-radius: 0.375rem; /* Bootstrap default border-radius */
    }
    .tagify__input{
        margin: 5px;
    }
    .tagify__tag{
        margin: 5px; /* Add some margin around tags */
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <form action="{{ route('patients.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>{{ __('patients.new_patient') }}</h5>
                        <a href="{{ route('patients.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left"></i> {{ __('general.back') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-3 fw-bold fs-5"><i class="bi bi-person-circle me-1"></i> {{ __('patients.section_personal') }}</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">{{ __('patients.name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="{{ __('patients.name_placeholder') }}">
                                <div class="invalid-feedback">{{ __('patients.name_required') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">{{ __('patients.email') }} <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="{{ __('patients.email_placeholder') }}">
                                <div class="invalid-feedback">{{ $errors->first('email') ?: __('validation.email', ['attribute' => __('patients.email')]) }}</div>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Telefon</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" style="width:100%; min-width:0;">
                                <input type="hidden" id="country_code" name="country_code" value="{{ old('country_code') }}">
                                @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="identity_number" class="form-label">{{ __('patients.identity_number') }}</label>
                                <input type="text" class="form-control @error('identity_number') is-invalid @enderror" id="identity_number" name="identity_number" value="{{ old('identity_number') }}" placeholder="11 haneli kimlik no">
                                @error('identity_number') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="birth_date" class="form-label">{{ __('patients.birth_date') }}</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                                @error('birth_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">{{ __('patients.gender') }}</label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                    <option value="">{{ __('general.select') }}</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('general.male') }}</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('general.female') }}</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>{{ __('general.other') }}</option>
                                </select>
                                @error('gender') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="profile_photo" class="form-label">{{ __('patients.profile_photo') }}</label>
                                <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" id="profile_photo" name="profile_photo" accept="image/*" onchange="previewPhoto(event)">
                                <div class="mt-2">
                                    <img id="photoPreview" src="#" alt="Önizleme" style="display:none; max-width:80px; max-height:80px; border-radius:50%;">
                                </div>
                                @error('profile_photo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3 fw-bold fs-5"><i class="bi bi-geo-alt me-1"></i> Adres Bilgileri</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label for="address" class="form-label">Adres</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" placeholder="Açık adres">{{ old('address') }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label for="city" class="form-label">Şehir</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="country" class="form-label">Ülke</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="postal_code" class="form-label">Posta Kodu</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3 fw-bold fs-5"><i class="bi bi-heart-pulse me-1"></i> {{ __('patients.section_health') }}</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="medical_history" class="form-label">{{ __('patients.medical_history') }}</label>
                                <textarea class="form-control @error('medical_history') is-invalid @enderror" id="medical_history" name="medical_history" rows="2">{{ old('medical_history') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="blood_type" class="form-label">{{ __('patients.blood_type') }}</label>
                                <input type="text" class="form-control @error('blood_type') is-invalid @enderror" id="blood_type" name="blood_type" value="{{ old('blood_type') }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="allergies" class="form-label">{{ __('patients.allergies') }}</label>
                                <input type="text" class="form-control tagify-input @error('allergies') is-invalid @enderror @error('allergies.*') is-invalid @enderror" id="allergies" name="allergies" value="{{ old('allergies') ? (is_array(old('allergies')) ? implode(',', old('allergies')) : old('allergies')) : '' }}" placeholder="Alerji ekleyin">
                                @error('allergies') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                @error('allergies.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="chronic_diseases" class="form-label">{{ __('patients.chronic_diseases') }}</label>
                                <input type="text" class="form-control tagify-input @error('chronic_diseases') is-invalid @enderror @error('chronic_diseases.*') is-invalid @enderror" id="chronic_diseases" name="chronic_diseases" value="{{ old('chronic_diseases') ? (is_array(old('chronic_diseases')) ? implode(',', old('chronic_diseases')) : old('chronic_diseases')) : '' }}" placeholder="Kronik hastalık ekleyin">
                                @error('chronic_diseases') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                @error('chronic_diseases.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="medications_used" class="form-label">{{ __('patients.medications_used') }}</label>
                                <input type="text" class="form-control tagify-input @error('medications_used') is-invalid @enderror @error('medications_used.*') is-invalid @enderror" id="medications_used" name="medications_used" value="{{ old('medications_used') ? (is_array(old('medications_used')) ? implode(',', old('medications_used')) : old('medications_used')) : '' }}" placeholder="İlaç ekleyin">
                                @error('medications_used') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                @error('medications_used.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3 fw-bold fs-5"><i class="bi bi-journal-text me-1"></i> {{ __('patients.notes') }}</h6>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="notes" class="form-label visually-hidden">{{ __('patients.notes') }}</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Hasta ile ilgili genel notlar...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> {{ __('general.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<!-- intl-tel-input: önce ana dosya, sonra utils.js (18.1.1 sürümü) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/utils.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>

<script>
    function previewPhoto(event) {
        const [file] = event.target.files;
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('photoPreview');
                img.src = e.target.result;
                img.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }

    $(document).ready(function() {
        var phoneInput = document.querySelector("#phone");
        if (phoneInput) {
            window.intlTelInput(phoneInput, {
                initialCountry: "tr",
                nationalMode: false,
                preferredCountries: ['tr', 'us', 'de'],
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/utils.js"
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

        // Regarding utils.js 'export' error:
        // The file at https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/25.3.1/build/js/utils.js 
        // should be a UMD file and not produce an 'export' error when loaded as a normal script.
        // 1. Double-check if there are any other scripts named 'utils.js' being loaded on your page.
        // 2. In your browser's developer tools (Network or Sources tab), inspect the content of the 
        //    'utils.js' file that is throwing the error to confirm if it indeed contains ES6 'export' statements 
        //    at the top level. If it does, it might be a different file or a CDN issue.
    });
</script>
@endpush 