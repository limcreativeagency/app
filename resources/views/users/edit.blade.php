@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>{{ ucfirst($role) }} Düzenle</h2>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('users.update', [$role, $user->id]) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Ad Soyad</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">E-posta</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Telefon</label>
                    <input type="tel" name="phone_visible" id="phone-input" class="form-control" value="{{ old('phone', $user->phone) }}" required autocomplete="off">
                    <input type="hidden" name="phone" id="phone-full" value="{{ old('phone', $user->phone) }}">
                    @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Şifre (değiştirmek istemiyorsanız boş bırakın)</label>
                    <input type="password" name="password" class="form-control">
                    @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Şifre Tekrar</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <input type="hidden" name="role" value="{{ $role }}">
                <button type="submit" class="btn btn-success">Güncelle</button>
                <a href="{{ route('users.index.' . $role) }}" class="btn btn-secondary">İptal</a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('intl-tel-input/js/intlTelInput.min.js') }}"></script>
<script src="{{ asset('intl-tel-input/js/utils.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var phoneInput = document.getElementById('phone-input');
    var phoneFull = document.getElementById('phone-full');
    var form = phoneInput ? phoneInput.closest('form') : null;
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
    if(form && phoneInput && phoneFull && window.intlTelInput) {
        var iti = window.intlTelInputGlobals.getInstance(phoneInput) || window.intlTelInput(phoneInput);
        form.addEventListener('submit', function(e) {
            if(iti && typeof iti.getNumber === 'function') {
                phoneFull.value = iti.getNumber();
            }
            phoneInput.setAttribute('name', '');
        });
    }
});
</script>
@endpush 