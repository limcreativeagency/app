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
                        <div class="mt-1 fw-semibold" style="color:#2563eb;">Klinik Bilgileri</div>
                    </div>
                    <div class="flex-fill border-top mx-2" style="border-width:2px !important; border-color:#d1d5db !important;"></div>
                    <div class="stepper-step text-center flex-fill">
                        <div class="step-circle bg-light text-secondary mx-auto" style="width:38px;height:38px;line-height:38px;border-radius:50%;font-size:1.2rem;">2</div>
                        <div class="mt-1 text-secondary">Yönetici Bilgileri</div>
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
                        <div class="fw-bold fs-3 mb-1" style="color:#2563eb; font-family:'Fredoka', 'Nunito', Arial, sans-serif;">Klinik Bilgileri</div>
                        <div class="mb-2" style="color:#4b5563;">Klinik bilgilerinizi eksiksiz girin ve devam edin.</div>
                    </div>
                    <form method="POST" action="{{ route('register.step1') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Klinik Adı</label>
                                <input type="text" name="title" class="form-control" value="{{ session('clinic_step1.title') }}">
                                @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Telefon</label>
                                <input type="text" name="phone" id="clinic-phone-input" class="form-control" value="{{ session('clinic_step1.phone') }}">
                                @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">E-posta</label>
                                <input type="email" name="email" class="form-control" value="{{ session('clinic_step1.email') }}">
                                @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Vergi Numarası</label>
                                <input type="text" name="tax_number" class="form-control" value="{{ session('clinic_step1.tax_number') }}">
                                @error('tax_number')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Şehir</label>
                                <input type="text" name="city" class="form-control" value="{{ session('clinic_step1.city') }}">
                                @error('city')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ülke</label>
                                <input type="text" name="country" class="form-control" value="{{ session('clinic_step1.country') }}">
                                @error('country')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Web Sitesi</label>
                                <input type="text" name="website" class="form-control" value="{{ session('clinic_step1.website') }}">
                                @error('website')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Adres</label>
                                <input type="text" name="address" class="form-control" value="{{ session('clinic_step1.address') }}">
                                @error('address')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Açıklama</label>
                                <textarea name="description" class="form-control" rows="4">{{ session('clinic_step1.description') }}</textarea>
                                @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Notlar</label>
                                <textarea name="notes" class="form-control" rows="4">{{ session('clinic_step1.notes') }}</textarea>
                                @error('notes')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Logo</label>
                                <input type="file" name="logo" class="form-control">
                                @error('logo')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100 mt-3" style="border-radius:1.5rem; font-weight:700; font-size:1.1rem;">Devam Et</button>
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
    var phoneInput = document.getElementById('clinic-phone-input');
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
                if(window.cleaveClinicTR) window.cleaveClinicTR.destroy();
                window.cleaveClinicTR = new Cleave(phoneInput, {
                    phone: true,
                    phoneRegionCode: 'TR',
                    prefix: '',
                    noImmediatePrefix: true,
                    delimiter: ' ',
                });
            } else if(window.cleaveClinicTR) {
                window.cleaveClinicTR.destroy();
            }
        }
        updateMask();
        phoneInput.addEventListener('countrychange', updateMask);
    }
});
</script>
@endpush 