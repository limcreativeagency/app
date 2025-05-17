@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>{{ __('clinic.hospital_edit_title') }}</h2>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('clinic.hospital.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="clinic_name">{{ __('clinic.clinic_name') }}</label>
                    <input type="text" class="form-control" id="clinic_name" name="clinic_name" value="{{ old('clinic_name', $hospital->clinic_name) }}" required>
                    @error('clinic_name')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('clinic.hospital_tax_number') }}</label>
                    <input type="text" name="tax_number" class="form-control" value="{{ old('tax_number', $hospital->tax_number) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('clinic.hospital_phone') }}</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $hospital->phone) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('clinic.hospital_email') }}</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $hospital->email) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('clinic.hospital_address') }}</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $hospital->address) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('clinic.hospital_city') }}</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $hospital->city) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('clinic.hospital_country') }}</label>
                    <input type="text" name="country" class="form-control" value="{{ old('country', $hospital->country) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('clinic.hospital_website') }}</label>
                    <input type="text" name="website" class="form-control" value="{{ old('website', $hospital->website) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('clinic.hospital_description') }}</label>
                    <textarea name="description" class="form-control">{{ old('description', $hospital->description) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('clinic.hospital_notes') }}</label>
                    <textarea name="notes" class="form-control">{{ old('notes', $hospital->notes) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('clinic.hospital_logo') }}</label>
                    @if($hospital->logo)
                        <div class="mb-2"><img src="{{ asset('storage/'.$hospital->logo) }}" alt="Logo" style="max-height:60px;"></div>
                    @endif
                    <input type="file" name="logo" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">{{ __('general.general.save') }}</button>
                <a href="{{ route('clinic.dashboard') }}" class="btn btn-secondary">{{ __('general.general.cancel') }}</a>
            </form>
        </div>
    </div>
</div>
@endsection 