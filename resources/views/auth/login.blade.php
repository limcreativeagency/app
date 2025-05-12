@extends('layouts.app')

@section('content')
<div class="login-outer d-flex align-items-center justify-content-center" style="min-height: 85vh; background: #f6fafd;">
    <div class="login-card d-flex flex-row shadow-lg" style="background: #fff; border-radius: 2.2rem; overflow: hidden; max-width: 820px; width: 100%;">
        <!-- Sol: Form Alanı -->
        <div class="login-form-side p-5 d-flex flex-column justify-content-center" style="flex:1; min-width:320px; max-width:420px;">
            <div class="text-start mb-4">
                <div class="fw-bold fs-2 mb-1" style="color:#2563eb; font-family:'Fredoka', 'Nunito', Arial, sans-serif;">{{ __('auth.brand') }}</div>
                <div class="mb-4" style="color:#4b5563; font-size:1.08rem;">{{ __('auth.subtitle') }}</div>
            </div>
            @if ($errors->any())
                <div class="text-danger small mb-2">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            <!-- Tab Nav -->
            <ul class="nav nav-tabs mb-4" id="loginTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="phone-tab" data-bs-toggle="tab" data-bs-target="#phone" type="button" role="tab" aria-controls="phone" aria-selected="true">{{ __('auth.tab_phone') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab" aria-controls="email" aria-selected="false">{{ __('auth.tab_email') }}</button>
                </li>
            </ul>
            <div class="tab-content" id="loginTabContent">
                <!-- Telefon ile Giriş -->
                <div class="tab-pane fade show active" id="phone" role="tabpanel" aria-labelledby="phone-tab">
                    <form method="POST" action="{{ route('login') }}" id="phone-login-form">
                        @csrf
                        <div class="mb-3">
                            <input type="tel" class="form-control" name="phone_visible" id="phone-input" placeholder="{{ __('auth.phone_placeholder') }}" autocomplete="off" required>
                            <input type="hidden" name="phone" id="phone-full">
                            @if (
                                $errors->has('phone'))
                                <span class="text-danger small">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                        <div class="mb-3 position-relative">
                            <input type="password" class="form-control" name="password" id="phone-password" placeholder="{{ __('auth.password_placeholder') }}" required>
                            <span class="toggle-password" toggle="#phone-password" style="position:absolute;top:50%;right:1rem;transform:translateY(-50%);cursor:pointer;"><i class="bi bi-eye-slash"></i></span>
                        </div>
                        <div class="mb-3 text-start">
                            {{-- Şifre sıfırlama linki, route yoksa hata vermesin --}}
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="small" style="color:#2563eb;">{{ __('auth.forgot') }}</a>
                            @else
                                <a href="#" class="small disabled" style="color:#b0b6c3; cursor:not-allowed;">{{ __('auth.forgot') }}</a>
                            @endif
                        </div>
                        <button type="submit" class="btn w-100" style="background:#2563eb; color:#fff; font-weight:700; border-radius:1.5rem; font-size:1.1rem; padding:0.7rem 0;">{{ __('auth.login') }}</button>
                    </form>
                </div>
                <!-- E-posta ile Giriş -->
                <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
                    <form method="POST" action="{{ route('login') }}" id="email-login-form">
                        @csrf
                        <div class="mb-3">
                            <input type="email" class="form-control" name="email" id="email-input" placeholder="E-posta" required>
                            @if ($errors->has('email'))
                                <span class="text-danger small">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="mb-3 position-relative">
                            <input type="password" class="form-control" name="password" id="email-password" placeholder="{{ __('auth.password_placeholder') }}" required>
                            <span class="toggle-password" toggle="#email-password" style="position:absolute;top:50%;right:1rem;transform:translateY(-50%);cursor:pointer;"><i class="bi bi-eye-slash"></i></span>
                        </div>
                        <div class="mb-3 text-start">
                            {{-- Şifre sıfırlama linki, route yoksa hata vermesin --}}
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="small" style="color:#2563eb;">{{ __('auth.forgot') }}</a>
                            @else
                                <a href="#" class="small disabled" style="color:#b0b6c3; cursor:not-allowed;">{{ __('auth.forgot') }}</a>
                            @endif
                        </div>
                        <button type="submit" class="btn w-100" style="background:#2563eb; color:#fff; font-weight:700; border-radius:1.5rem; font-size:1.1rem; padding:0.7rem 0;">{{ __('auth.login') }}</button>
                    </form>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('register.step1') }}" style="color:#2563eb; font-weight:600;">{{ __('auth.register') }}</a>
            </div>
            <div class="text-center mt-4 small" style="color:#b0b6c3;">
                © 2025 {{ __('auth.brand') }} | <a href="#" style="color:#2563eb;">{{ __('auth.help') }}</a> | <a href="#" style="color:#2563eb;">{{ __('auth.privacy') }}</a> | <a href="#" style="color:#2563eb;">{{ __('auth.terms') }}</a>
            </div>
        </div>
        <!-- Sağ: Maskot Görseli -->
        <div class="login-maskot-side d-flex align-items-center justify-content-center" style="background:#eaf2ff; flex:1; min-width:280px;">
            <img src="{{ asset('images/maskot.png') }}" alt="Robot" class="img-fluid" style="max-height:340px;">
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="{{ asset('intl-tel-input/css/intlTelInput.min.css') }}">
<script src="{{ asset('intl-tel-input/js/intlTelInput.min.js') }}"></script>
<script src="{{ asset('intl-tel-input/js/utils.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var phoneInput = document.getElementById('phone-input');
    if(phoneInput && window.intlTelInput) {
        var iti = window.intlTelInput(phoneInput, {
            initialCountry: 'tr',
            nationalMode: true,
            formatOnDisplay: true,
            utilsScript: '{{ asset('intl-tel-input/js/utils.js') }}',
            placeholderNumberType: 'MOBILE',
            autoHideDialCode: false,
            separateDialCode: true
        });
        // Cleave.js ile maske
        var cleaveTR = null;
        function updateMask() {
            var country = iti.getSelectedCountryData();
            if (country.iso2 === 'tr') {
                if (!cleaveTR) {
                    cleaveTR = new Cleave(phoneInput, {
                        delimiters: [' ', ' ', ' ', ' '],
                        blocks: [3, 3, 2, 2],
                        numericOnly: true
                    });
                }
            } else {
                if (cleaveTR) {
                    cleaveTR.destroy();
                    cleaveTR = null;
                    phoneInput.value = '';
                }
            }
        }
        updateMask();
        phoneInput.addEventListener('countrychange', updateMask);
    }
    // Şifre göster/gizle
    document.querySelectorAll('.toggle-password').forEach(function(el) {
        el.addEventListener('click', function() {
            var input = document.querySelector(this.getAttribute('toggle'));
            if(input.type === 'password') {
                input.type = 'text';
                this.innerHTML = '<i class="bi bi-eye"></i>';
            } else {
                input.type = 'password';
                this.innerHTML = '<i class="bi bi-eye-slash"></i>';
            }
        });
    });
    // Tab çakışma önleme: Sadece aktif tabdaki form enable, diğeri disable
    function toggleFormInputs() {
        var phoneTabActive = document.getElementById('phone').classList.contains('show') && document.getElementById('phone').classList.contains('active');
        document.querySelectorAll('#phone-login-form input').forEach(function(input) {
            input.disabled = !phoneTabActive;
        });
        document.querySelectorAll('#email-login-form input').forEach(function(input) {
            input.disabled = phoneTabActive;
        });
    }
    var loginTab = document.getElementById('loginTab');
    if(loginTab) {
        loginTab.addEventListener('click', function(e) {
            setTimeout(toggleFormInputs, 100); // Tab geçişinden sonra
        });
        toggleFormInputs(); // İlk yüklemede
    }
    var phoneForm = document.getElementById('phone-login-form');
    var phoneInput = document.getElementById('phone-input');
    var phoneFull = document.getElementById('phone-full');
    if(phoneForm && phoneInput && phoneFull && window.intlTelInput) {
        var iti = window.intlTelInputGlobals.getInstance(phoneInput) || window.intlTelInput(phoneInput);
        phoneForm.addEventListener('submit', function(e) {
            if(iti && typeof iti.getNumber === 'function') {
                phoneFull.value = iti.getNumber();
            }
            phoneInput.setAttribute('name', '');
        });
    }
});
</script>
@endsection 