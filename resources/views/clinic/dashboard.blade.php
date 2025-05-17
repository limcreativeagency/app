@extends('layouts.app')

@section('content')
    <div class="container py-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                {{ session('success') }}
            </div>
        @endif
        <h1 class="mb-4">{{ __('clinic.dashboard_title') }}</h1>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ __('clinic.hospital_info') }}</h5>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item"><strong>{{ __('clinic.clinic_name') }}:</strong> {{ $hospital->clinic_name }}</li>
                    <li class="list-group-item"><strong>{{ __('clinic.hospital_phone') }}:</strong> {{ $hospital->phone }}</li>
                    <li class="list-group-item"><strong>{{ __('clinic.hospital_email') }}:</strong> {{ $hospital->email }}</li>
                    <li class="list-group-item"><strong>{{ __('clinic.hospital_tax_number') }}:</strong> {{ $hospital->tax_number }}</li>
                    <li class="list-group-item"><strong>{{ __('clinic.hospital_address') }}:</strong> {{ $hospital->address }}</li>
                    <li class="list-group-item"><strong>{{ __('clinic.hospital_city') }}:</strong> {{ $hospital->city }}</li>
                    <li class="list-group-item"><strong>{{ __('clinic.hospital_country') }}:</strong> {{ $hospital->country }}</li>
                    {{-- <li class="list-group-item"><strong>{{ __('clinic.hospital_trial_start') }}:</strong> {{ $hospital->trial_start_date }}</li> --}}
                    {{-- <li class="list-group-item"><strong>{{ __('clinic.hospital_trial_end') }}:</strong> {{ $hospital->trial_end_date }}</li> --}}
                    {{-- <li class="list-group-item"><strong>{{ __('clinic.hospital_subscription_start') }}:</strong> {{ $hospital->subscription_start_date }}</li> --}}
                    {{-- <li class="list-group-item"><strong>{{ __('clinic.hospital_subscription_end') }}:</strong> {{ $hospital->subscription_end_date }}</li> --}}
                    <li class="list-group-item">
                        <strong>{{ __('clinic.hospital_status') }}:</strong>
                        @if($hospital->status === 'trial' && $hospital->trial_end_date)
                            @php
                                $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($hospital->trial_end_date), false);
                            @endphp
                            <span class="text-info ms-2">
                                {{ __('clinic.trial_days_left', ['days' => $daysLeft > 0 ? $daysLeft : 0]) }}
                            </span>
                        @else
                            {{ __('clinic.hospital_status_' . $hospital->status) }}
                        @endif
                    </li>
                    <li class="list-group-item"><strong>{{ __('clinic.hospital_logo') }}:</strong> @if($hospital->logo)<img src="{{ asset('storage/'.$hospital->logo) }}" alt="Logo" style="max-height:40px;">@endif</li>
                    <li class="list-group-item"><strong>{{ __('clinic.hospital_description') }}:</strong> {{ $hospital->description }}</li>
                    <li class="list-group-item"><strong>{{ __('clinic.hospital_website') }}:</strong> {{ $hospital->website }}</li>
                    <li class="list-group-item"><strong>{{ __('clinic.hospital_notes') }}:</strong> {{ $hospital->notes }}</li>
                </ul>
                <a href="{{ route('clinic.hospital.edit') }}" class="btn btn-primary">{{ __('general.general.edit') }}</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(function () {
                    alert.remove();
                }, 3000);
            }
        });
    </script>
@endsection 