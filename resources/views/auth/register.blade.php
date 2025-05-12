@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@900&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
<div class="login-outer d-flex justify-content-center">
    <div class="login-card d-flex flex-row shadow-lg" style="background: #fff; border-radius: 2.2rem; overflow: hidden; max-width: 820px; width: 100%;">
        <!-- Sol: Form Alanı -->
        <div class="login-form-side p-5 d-flex flex-column justify-content-center" style="flex:1; min-width:320px; max-width:420px;">
            <div class="text-start mb-4">
                <div class="fw-bold fs-2 mb-1" style="color:#2563eb; font-family:'Fredoka', 'Nunito', Arial, sans-serif;">{{ __('auth.brand') }}</div>
                <div class="mb-4" style="color:#4b5563; font-size:1.08rem;">{{ __('auth.register_subtitle', [], app()->getLocale()) ?? 'Kayıt Olun ve hasta takip panelinizi kullanmaya başlayın.' }}</div>
            </div>
            <form method="POST" action="{{ route('register') }}" id="register-form">
                @csrf
                <div class="mb-3">
                    <input type="text" class="form-control" name="name" placeholder="{{ __('auth.name_placeholder', [], app()->getLocale()) ?? 'Ad' }}" required autofocus>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" name="surname" placeholder="{{ __('auth.surname_placeholder', [], app()->getLocale()) ?? 'Soyad' }}" required>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="{{ __('auth.email_placeholder', [], app()->getLocale()) ?? 'E-posta' }}" required>
                </div>
                <div class="mb-3">
                    <input type="tel" class="form-control" name="phone_visible" id="register-phone-input" placeholder="{{ __('auth.phone_placeholder') }}" autocomplete="off" required>
                </div>
                <input type="hidden" name="phone" id="register-phone-full">
                <div class="mb-3 position-relative">
                    <input type="password" class="form-control" name="password" id="register-password" placeholder="{{ __('auth.password_placeholder') }}" required>
                    <span class="toggle-password" toggle="#register-password" style="position:absolute;top:50%;right:1rem;transform:translateY(-50%);cursor:pointer;"><i class="bi bi-eye-slash"></i></span>
                </div>
                <div class="mb-3 position-relative">
                    <input type="password" class="form-control" name="password_confirmation" id="register-password-confirm" placeholder="{{ __('auth.password_confirm_placeholder', [], app()->getLocale()) ?? 'Şifre Tekrar' }}" required>
                    <span class="toggle-password" toggle="#register-password-confirm" style="position:absolute;top:50%;right:1rem;transform:translateY(-50%);cursor:pointer;"><i class="bi bi-eye-slash"></i></span>
                </div>
                <button type="submit" class="btn w-100" style="background:#2563eb; color:#fff; font-weight:700; border-radius:1.5rem; font-size:1.1rem; padding:0.7rem 0;">{{ __('auth.register') }}</button>
            </form>
            <div class="text-center mt-3">
                <span>{{ __('auth.already_account', [], app()->getLocale()) ?? 'Zaten hesabın var mı?' }}</span> <a href="{{ route('login') }}" style="color:#2563eb; font-weight:600;">{{ __('auth.login') }}</a>
            </div>
            <div class="text-center mt-4 small" style="color:#b0b6c3;">
                © 2025 {{ __('auth.brand') }} | <a href="#" style="color:#2563eb;">{{ __('auth.help') }}</a> | <a href="#" style="color:#2563eb;">{{ __('auth.privacy') }}</a> | <a href="#" style="color:#2563eb;">{{ __('auth.terms') }}</a>
            </div>
        </div>
        <!-- Sağ: Maskot Görseli -->
        <div class="login-maskot-side d-flex align-items-center justify-content-center" style="background:#eaf2ff; flex:1; min-width:280px;">
            <img src="{{ asset('images/maskot.png') }}" alt="Robot" class="img-fluid" style="max-height:450px;">
        </div>
    </div>
</div>
<link rel="stylesheet" href="{{ asset('intl-tel-input/css/intlTelInput.min.css') }}">
<script src="{{ asset('intl-tel-input/js/intlTelInput.min.js') }}"></script>
<script src="{{ asset('intl-tel-input/js/utils.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // intl-tel-input + Cleave.js maske
    var phoneInput = document.getElementById('register-phone-input');
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
    var registerForm = document.getElementById('register-form');
    var registerPhoneInput = document.getElementById('register-phone-input');
    var registerPhoneFull = document.getElementById('register-phone-full');
    if(registerForm && registerPhoneInput && registerPhoneFull && window.intlTelInput) {
        var itiReg = window.intlTelInputGlobals.getInstance(registerPhoneInput) || window.intlTelInput(registerPhoneInput);
        registerForm.addEventListener('submit', function(e) {
            if(itiReg && typeof itiReg.getNumber === 'function') {
                registerPhoneFull.value = itiReg.getNumber();
            }
            registerPhoneInput.setAttribute('name', '');
        });
    }
});
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
