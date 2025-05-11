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
                        <div class="mt-1 text-secondary">Klinik Bilgileri</div>
                    </div>
                    <div class="flex-fill border-top mx-2" style="border-width:2px !important; border-color:#d1d5db !important;"></div>
                    <div class="stepper-step text-center flex-fill">
                        <div class="step-circle bg-primary text-white fw-bold mx-auto" style="width:38px;height:38px;line-height:38px;border-radius:50%;font-size:1.2rem;">2</div>
                        <div class="mt-1 fw-semibold" style="color:#2563eb;">Yönetici Bilgileri</div>
                    </div>
                    <div class="flex-fill border-top mx-2" style="border-width:2px !important; border-color:#d1d5db !important;"></div>
                    <div class="stepper-step text-center flex-fill">
                        <div class="step-circle bg-light text-secondary mx-auto" style="width:38px;height:38px;line-height:38px;border-radius:50%;font-size:1.2rem;">3</div>
                        <div class="mt-1 text-secondary">SMS Onayı</div>
                    </div>
                </div>
                <!-- Form Kartı -->
                <div class="card shadow-lg p-4" style="border-radius:2rem;">
                    <div class="text-center mb-3">
                        <div class="fw-bold fs-3 mb-1" style="color:#2563eb; font-family:'Fredoka', 'Nunito', Arial, sans-serif;">Yönetici Bilgileri</div>
                        <div class="mb-2" style="color:#4b5563;">Klinik yöneticisi olarak kayıt olmak için bilgilerinizi girin.</div>
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
                            <div class="col-md-6">
                                <label class="form-label">Ad</label>
                                <input type="text" name="admin_name" class="form-control" value="{{ session('clinic_step2.admin_name') }}">
                                @error('admin_name')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">E-posta</label>
                                <input type="email" name="admin_email" class="form-control" value="{{ session('clinic_step2.admin_email') }}">
                                @error('admin_email')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Telefon</label>
                                <input type="text" name="admin_phone" id="admin-phone-input" class="form-control" value="{{ session('clinic_step2.admin_phone') }}">
                                @error('admin_phone')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Şifre</label>
                                <input type="password" name="admin_password" class="form-control">
                                @error('admin_password')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Şifre Tekrar</label>
                                <input type="password" name="admin_password_confirmation" class="form-control">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3 gap-2">
                            <a href="{{ route('register.step1') }}" class="btn btn-link px-0" style="color:#2563eb; font-weight:600;"><i class="bi bi-arrow-left"></i> Geri</a>
                            <button type="submit" class="btn btn-success px-4" style="border-radius:1.5rem; font-weight:700; font-size:1.1rem; min-width:120px;">Devam Et</button>
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.min.css" onerror="this.onerror=null;this.href='{{ asset('intl-tel-input/css/intlTelInput.min.css') }}';" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js" onerror="this.onerror=null;this.src='{{ asset('intl-tel-input/js/intlTelInput.min.js') }}';"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js" onerror="this.onerror=null;this.src='{{ asset('intl-tel-input/js/utils.js') }}';"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js" onerror="this.onerror=null;this.src='https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js';"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var phoneInput = document.getElementById('admin-phone-input');
    var form = phoneInput ? phoneInput.closest('form') : null;
    if(phoneInput && window.intlTelInput) {
        var iti = window.intlTelInputGlobals.getInstance(phoneInput) || window.intlTelInput(phoneInput);
        if(form && iti) {
            form.addEventListener('submit', function(e) {
                phoneInput.value = iti.getNumber();
            });
        }
        // Eğer value varsa, intl-tel-input'a set et
        if(phoneInput.value) {
            var raw = phoneInput.value.replace(/\D/g, '');
            if(raw.length === 10) {
                phoneInput.value = '+90' + raw;
            } else if(raw.length === 11 && raw.startsWith('0')) {
                phoneInput.value = '+90' + raw.substring(1);
            }
            iti.setNumber(phoneInput.value);
        }
        function updateMask() {
            var country = iti.getSelectedCountryData();
            if (country.iso2 === 'tr') {
                if(window.cleaveAdminTR) window.cleaveAdminTR.destroy();
                window.cleaveAdminTR = new Cleave(phoneInput, {
                    phone: true,
                    phoneRegionCode: 'TR',
                    prefix: '',
                    noImmediatePrefix: true,
                    delimiter: ' ',
                });
            } else if(window.cleaveAdminTR) {
                window.cleaveAdminTR.destroy();
            }
        }
        updateMask();
        phoneInput.addEventListener('countrychange', updateMask);
    }
});
</script>
@endpush 