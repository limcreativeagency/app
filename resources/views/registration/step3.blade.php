@extends('layouts.app')
@section('content')
<div class="welcome-outer" style="min-height:100vh; background:#eaf2ff;">
    <div class="container py-5">
        <div class="d-flex flex-column flex-lg-row align-items-center justify-content-center gap-4">
            <!-- Sol: Form ve Stepper -->
            <div style="max-width:480px; width:100%;">
                <!-- Stepper -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="stepper-step text-center flex-fill">
                        <div class="step-circle bg-light text-secondary mx-auto" style="width:38px;height:38px;line-height:38px;border-radius:50%;font-size:1.2rem;">1</div>
                        <div class="mt-1 text-secondary">Klinik Bilgileri</div>
                    </div>
                    <div class="flex-fill border-top mx-2" style="border-width:2px !important; border-color:#d1d5db !important;"></div>
                    <div class="stepper-step text-center flex-fill">
                        <div class="step-circle bg-light text-secondary mx-auto" style="width:38px;height:38px;line-height:38px;border-radius:50%;font-size:1.2rem;">2</div>
                        <div class="mt-1 text-secondary">Yönetici Bilgileri</div>
                    </div>
                    <div class="flex-fill border-top mx-2" style="border-width:2px !important; border-color:#d1d5db !important;"></div>
                    <div class="stepper-step text-center flex-fill">
                        <div class="step-circle bg-primary text-white fw-bold mx-auto" style="width:38px;height:38px;line-height:38px;border-radius:50%;font-size:1.2rem;">3</div>
                        <div class="mt-1 fw-semibold" style="color:#2563eb;">SMS Onayı</div>
                    </div>
                </div>
                <!-- Form Kartı -->
                <div class="card shadow-lg p-4" style="border-radius:2rem;">
                    <div class="text-center mb-3">
                        <div class="fw-bold fs-3 mb-1" style="color:#2563eb; font-family:'Fredoka', 'Nunito', Arial, sans-serif;">SMS Doğrulama</div>
                        <div class="mb-2" style="color:#4b5563;">Telefonunuza gelen SMS kodunu girin (Demo kod: 1234)</div>
                    </div>
                    <form method="POST" action="{{ route('register.step3') }}">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="sms_code" class="form-control" value="{{ old('sms_code') }}" placeholder="SMS Kodu">
                            @error('sms_code')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-success w-100 mt-2" style="border-radius:1.5rem; font-weight:700; font-size:1.1rem;">Kaydı Tamamla</button>
                        <div class="d-flex justify-content-start mt-2">
                            <a href="{{ route('register.step2') }}" class="btn btn-link px-0" style="color:#2563eb; font-weight:600;"><i class="bi bi-arrow-left"></i> Geri</a>
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