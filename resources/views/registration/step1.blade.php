@extends('layouts.app')
@section('content')
<div class="welcome-outer" style="min-height:100vh; background:#eaf2ff;">
    <div class="container py-3">
        <div class="d-flex flex-column flex-lg-row align-items-center justify-content-center gap-4">
            <!-- Sol: Form ve Stepper -->
            <div style="max-width:800px; width:100%;">
                <!-- Stepper -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="stepper-step text-center flex-fill">
                        <div class="step-circle bg-primary text-white fw-bold mx-auto" style="width:38px;height:38px;line-height:38px;border-radius:50%;font-size:1.2rem;">1</div>
                        <div class="mt-1 fw-semibold" style="color:#2563eb;">{{ __('register_step1.step1') }}</div>
                    </div>
                    <div class="flex-fill border-top mx-2" style="border-width:2px !important; border-color:#d1d5db !important;"></div>
                    <div class="stepper-step text-center flex-fill">
                        <div class="step-circle bg-light text-secondary mx-auto" style="width:38px;height:38px;line-height:38px;border-radius:50%;font-size:1.2rem;">2</div>
                        <div class="mt-1 text-secondary">{{ __('register_step1.step2') }}</div>
                    </div>
                    <div class="flex-fill border-top mx-2" style="border-width:2px !important; border-color:#d1d5db !important;"></div>
                    <div class="stepper-step text-center flex-fill">
                        <div class="step-circle bg-light text-secondary mx-auto" style="width:38px;height:38px;line-height:38px;border-radius:50%;font-size:1.2rem;">3</div>
                        <div class="mt-1 text-secondary">{{ __('register_step1.step3') }}</div>
                    </div>
                </div>
                <!-- Form Kartı -->
                <div class="card shadow-lg p-4" style="border-radius:2rem;">
                    <div class="text-center mb-3">
                        <div class="fw-bold fs-3 mb-1" style="color:#2563eb; font-family:'Fredoka', 'Nunito', Arial, sans-serif;">{{ __('register_step1.clinic_info_title') }}</div>
                        <div class="mb-2" style="color:#4b5563;">{{ __('register_step1.clinic_info_subtitle') }}</div>
                    </div>
                    <form method="POST" action="{{ route('register.step1') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">{{ __('clinic.clinic_name') }}</label>
                                <input type="text" name="clinic_name" class="form-control" value="{{ session('clinic_step1.clinic_name') }}" required>
                                @error('clinic_name')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1" for="clinic-phone-input">{{ __('register_step1.phone') }}</label>
                                <div style="width:100%;">
                                    <input type="text" name="phone_visible" id="clinic-phone-input" class="form-control w-100" style="width:100%;" value="{{ session('clinic_step1.phone') ? ltrim(preg_replace('/^\\+\\d+/', '', session('clinic_step1.phone')), '0') : '' }}" placeholder="501 234 56 78" autocomplete="off">
                                </div>
                                <input type="hidden" name="phone_country" id="clinic-phone-country" value="">
                                <input type="hidden" name="phone" id="clinic-phone-full" value="">
                                @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('register_step1.email') }}</label>
                                <input type="email" name="email" class="form-control" value="{{ session('clinic_step1.email') }}">
                                @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('register_step1.tax_number') }}</label>
                                <input type="text" name="tax_number" class="form-control" value="{{ session('clinic_step1.tax_number') }}">
                                @error('tax_number')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('register_step1.city') }}</label>
                                <input type="text" name="city" class="form-control" value="{{ session('clinic_step1.city') }}">
                                @error('city')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('register_step1.country') }}</label>
                                <input type="text" name="country" class="form-control" value="{{ session('clinic_step1.country') }}">
                                @error('country')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('register_step1.website') }}</label>
                                <input type="text" name="website" class="form-control" value="{{ old('website', session('clinic_step1.website')) }}">
                                @error('website')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('register_step1.address') }}</label>
                                <input type="text" name="address" class="form-control" value="{{ session('clinic_step1.address') }}">
                                @error('address')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('register_step1.description') }}</label>
                                <textarea name="description" class="form-control" rows="4">{{ session('clinic_step1.description') }}</textarea>
                                @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('register_step1.notes') }}</label>
                                <textarea name="notes" class="form-control" rows="4">{{ session('clinic_step1.notes') }}</textarea>
                                @error('notes')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('register_step1.logo') }}</label>
                                <input type="file" name="logo" class="form-control">
                                @error('logo')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100 mt-3" style="border-radius:1.5rem; font-weight:700; font-size:1.1rem;">{{ __('register_step1.continue') }}</button>
                    </form>
                </div>
            </div>
            <!-- Sağ: Maskot -->
            <div class="text-center flex-shrink-0 mb-4 mb-lg-0" style="min-width:260px;">
                <img src="{{ asset('images/maskot.png') }}" alt="Robot" class="img-fluid" style="max-height: 340px;">
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('intl-tel-input/css/intlTelInput.min.css') }}">
<style>
.intl-tel-input, .intl-tel-input .form-control {
    width: 100% !important;
    min-width: 0 !important;
}
.intl-tel-input .form-control, .form-control {
    height: 44px;
    box-sizing: border-box;
}
.intl-tel-input {
    display: block;
}
.intl-tel-input .iti__flag-container {
    left: 8px;
}
.col-md-6 .intl-tel-input, .col-md-6 .form-control {
    width: 100% !important;
}
.iti__country-list input[type=search] {
    display: block !important;
    width: 95% !important;
    margin: 8px auto !important;
    padding: 6px 12px !important;
    font-size: 15px !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js" onerror="this.onerror=null;this.src='{{ asset('intl-tel-input/js/intlTelInput.min.js') }}';"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js" onerror="this.onerror=null;this.src='{{ asset('intl-tel-input/js/utils.js') }}';"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js" onerror="this.onerror=null;this.src='https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js';"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var phoneInput = document.getElementById('clinic-phone-input');
    var countryInput = document.getElementById('clinic-phone-country');
    var fullInput = document.getElementById('clinic-phone-full');
    var form = phoneInput ? phoneInput.closest('form') : null;
    if(phoneInput && window.intlTelInput) {
        var iti = window.intlTelInputGlobals.getInstance(phoneInput) || window.intlTelInput(phoneInput, {
            initialCountry: 'tr',
            formatOnDisplay: false,
            allowDropdown: true,
            utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js'
        });
        // Cleave.js ile maske: 5xx xxx xx xx (sadece Türkiye için)
        var cleaveTR = null;
        function updateCleaveMask() {
            var country = iti.getSelectedCountryData();
            if (country && country.iso2 === 'tr') {
                if (cleaveTR) cleaveTR.destroy();
                cleaveTR = new Cleave(phoneInput, {
                    delimiters: [' ', ' ', ' ', ' '],
                    blocks: [3, 3, 2, 2],
                    numericOnly: true,
                    prefix: '',
                    noImmediatePrefix: true
                });
            } else {
                if (cleaveTR) cleaveTR.destroy();
                cleaveTR = new Cleave(phoneInput, {
                    numericOnly: true
                });
            }
        }
        updateCleaveMask();
        // --- Placeholder güncelleme fonksiyonu ---
        function updatePlaceholder() {
            if (iti && typeof iti.getPlaceholder === 'function') {
                var example = iti.getPlaceholder();
                if (example) {
                    phoneInput.setAttribute('placeholder', example);
                } else {
                    phoneInput.setAttribute('placeholder', '501 234 56 78');
                }
            } else {
                phoneInput.setAttribute('placeholder', '501 234 56 78');
            }
        }
        function waitForUtilsAndUpdatePlaceholder() {
            if (typeof window.intlTelInputUtils !== 'undefined') {
                updatePlaceholder();
            } else {
                setTimeout(waitForUtilsAndUpdatePlaceholder, 100);
            }
        }
        waitForUtilsAndUpdatePlaceholder();
        phoneInput.addEventListener('countrychange', function() {
            updateCleaveMask();
            if (!phoneInput.value) updatePlaceholder();
            if (countryInput) countryInput.value = iti.getSelectedCountryData().dialCode;
        });
        if (countryInput) countryInput.value = iti.getSelectedCountryData().dialCode;
        if(form && iti) {
            form.addEventListener('submit', function(e) {
                let val = phoneInput.value.trim();
                if(val.startsWith('0')) {
                    val = val.replace(/^0+/, '');
                }
                val = val.replace(/\D/g, '');
                var dialCode = iti.getSelectedCountryData().dialCode;
                if (dialCode && val) {
                    fullInput.value = '+' + dialCode + val;
                } else {
                    fullInput.value = val;
                }
                // inputun adını değiştirip sadece hidden'ı gönder
                phoneInput.setAttribute('name', '');
            });
        }
    }
});
</script>
@endpush 