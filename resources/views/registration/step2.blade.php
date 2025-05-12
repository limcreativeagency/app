@extends('layouts.app')
@section('content')
<div class="welcome-outer" style="min-height:100vh; background:#eaf2ff;">
    <div class="container py-5">
        <div class="d-flex flex-column flex-lg-row align-items-center justify-content-center gap-4">
            <!-- Sağ: Form ve Stepper -->
            <div style="max-width:480px; width:100%;">
                <!-- Stepper -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="stepper-step text-center flex-fill">
                        <div class="step-circle bg-light text-secondary mx-auto" style="width:38px;height:38px;line-height:38px;border-radius:50%;font-size:1.2rem;">1</div>
                        <div class="mt-1 text-secondary">{{ __('register_step2.step1') }}</div>
                    </div>
                    <div class="flex-fill border-top mx-2" style="border-width:2px !important; border-color:#d1d5db !important;"></div>
                    <div class="stepper-step text-center flex-fill">
                        <div class="step-circle bg-primary text-white fw-bold mx-auto" style="width:38px;height:38px;line-height:38px;border-radius:50%;font-size:1.2rem;">2</div>
                        <div class="mt-1 fw-semibold" style="color:#2563eb;">{{ __('register_step2.step2') }}</div>
                    </div>
                    <div class="flex-fill border-top mx-2" style="border-width:2px !important; border-color:#d1d5db !important;"></div>
                    <div class="stepper-step text-center flex-fill">
                        <div class="step-circle bg-light text-secondary mx-auto" style="width:38px;height:38px;line-height:38px;border-radius:50%;font-size:1.2rem;">3</div>
                        <div class="mt-1 text-secondary">{{ __('register_step2.step3') }}</div>
                    </div>
                </div>
                <!-- Form Kartı -->
                <div class="card shadow-lg p-4" style="border-radius:2rem;">
                    <div class="text-center mb-3">
                        <div class="fw-bold fs-3 mb-1" style="color:#2563eb; font-family:'Fredoka', 'Nunito', Arial, sans-serif;">{{ __('register_step2.admin_info_title') }}</div>
                        <div class="mb-2" style="color:#4b5563;">{{ __('register_step2.admin_info_subtitle') }}</div>
                    </div>
                    <form method="POST" action="{{ route('register.step2') }}">
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
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">{{ __('register_step2.admin_name') }}</label>
                                <input type="text" name="admin_name" class="form-control" value="{{ session('clinic_step2.admin_name') }}">
                                @error('admin_name')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('register_step2.admin_email') }}</label>
                                <input type="email" name="admin_email" class="form-control" value="{{ session('clinic_step2.admin_email') }}">
                                @error('admin_email')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('register_step2.admin_phone') }}</label>
                                <input type="text" name="admin_phone_visible" id="admin-phone-input" class="form-control w-100" value="{{ session('clinic_step2.admin_phone') ? ltrim(preg_replace('/^\\+\\d+/', '', session('clinic_step2.admin_phone')), '0') : '' }}" placeholder="501 234 56 78" autocomplete="off">
                                <input type="hidden" name="admin_phone_country" id="admin-phone-country" value="">
                                <input type="hidden" name="admin_phone" id="admin-phone-full" value="">
                                @error('admin_phone')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('register_step2.admin_password') }}</label>
                                <input type="password" name="admin_password" class="form-control">
                                @error('admin_password')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('register_step2.admin_password_confirmation') }}</label>
                                <input type="password" name="admin_password_confirmation" class="form-control">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3 gap-2">
                            <a href="{{ route('register.step1') }}" class="btn btn-link px-0" style="color:#2563eb; font-weight:600;"><i class="bi bi-arrow-left"></i> {{ __('register_step2.back') }}</a>
                            <button type="submit" class="btn btn-success px-4" style="border-radius:1.5rem; font-weight:700; font-size:1.1rem; min-width:120px;">{{ __('register_step2.continue') }}</button>
                        </div>
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
<script src="{{ asset('intl-tel-input/js/intlTelInput.min.js') }}"></script>
<script src="{{ asset('intl-tel-input/js/utils.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js" onerror="this.onerror=null;this.src='https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js';"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var phoneInput = document.getElementById('admin-phone-input');
    var countryInput = document.getElementById('admin-phone-country');
    var fullInput = document.getElementById('admin-phone-full');
    var form = phoneInput ? phoneInput.closest('form') : null;
    if(phoneInput && window.intlTelInput) {
        var iti = window.intlTelInputGlobals.getInstance(phoneInput) || window.intlTelInput(phoneInput, {
            initialCountry: 'tr',
            formatOnDisplay: false,
            allowDropdown: true,
            utilsScript: '{{ asset('intl-tel-input/js/utils.js') }}'
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