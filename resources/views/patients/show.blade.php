@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('patients.patient_details', ['name' => $patient->user->name]) }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('patients.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('general.back') }}
                        </a>
                        <a href="{{ route('patients.edit', $patient) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> {{ __('general.edit') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                @if($patient->profile_photo)
                                    <img src="{{ Storage::url($patient->profile_photo) }}" 
                                         alt="{{ $patient->user->name }}" 
                                         class="img-thumbnail" 
                                         style="max-width: 200px;">
                                @else
                                    <img src="{{ asset('images/default-avatar.png') }}" 
                                         alt="{{ $patient->user->name }}" 
                                         class="img-thumbnail" 
                                         style="max-width: 200px;">
                                @endif
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('patients.section_personal') }}</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%">{{ __('patients.name') }}</th>
                                            <td>{{ $patient->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.email') }}</th>
                                            <td>{{ $patient->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.phone') }}</th>
                                            <td>{{ $patient->user->phone ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.identity_number') }}</th>
                                            <td>{{ $patient->identity_number ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.birth_date') }}</th>
                                            <td>{{ $patient->birth_date ? $patient->birth_date->format('d.m.Y') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.gender') }}</th>
                                            <td>
                                                @switch($patient->gender)
                                                    @case('male')
                                                        {{ __('general.male') }}
                                                        @break
                                                    @case('female')
                                                        {{ __('general.female') }}
                                                        @break
                                                    @case('other')
                                                        {{ __('general.other') }}
                                                        @break
                                                    @default
                                                        -
                                                @endswitch
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('patients.section_address') }}</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%">{{ __('patients.address') }}</th>
                                            <td>{{ $patient->address ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.city') }}</th>
                                            <td>{{ $patient->city ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.country') }}</th>
                                            <td>{{ $patient->country ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.postal_code') }}</th>
                                            <td>{{ $patient->postal_code ?: '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('patients.section_health') }}</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%">{{ __('patients.medical_history') }}</th>
                                            <td>{{ $patient->medical_history ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.allergies') }}</th>
                                            <td>
                                                @if(!empty($patient->allergies) && is_array($patient->allergies))
                                                    @foreach($patient->allergies as $allergy)
                                                        <span class="badge bg-info text-dark me-1 mb-1">{{ $allergy }}</span>
                                                    @endforeach
                                                @elseif(!empty($patient->allergies)) {{-- Fallback for non-array string --}}
                                                    <span class="badge bg-info text-dark">{{ $patient->allergies }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.chronic_diseases') }}</th>
                                            <td>
                                                @if(!empty($patient->chronic_diseases) && is_array($patient->chronic_diseases))
                                                    @foreach($patient->chronic_diseases as $disease)
                                                        <span class="badge bg-warning text-dark me-1 mb-1">{{ $disease }}</span>
                                                    @endforeach
                                                @elseif(!empty($patient->chronic_diseases)) {{-- Fallback for non-array string --}}
                                                    <span class="badge bg-warning text-dark">{{ $patient->chronic_diseases }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.blood_type') }}</th>
                                            <td>{{ $patient->blood_type ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.medications_used') }}</th>
                                            <td>
                                                @if(!empty($patient->medications_used) && is_array($patient->medications_used))
                                                    @foreach($patient->medications_used as $medication)
                                                        <span class="badge bg-success text-white me-1 mb-1">{{ $medication }}</span>
                                                    @endforeach
                                                @elseif(!empty($patient->medications_used)) {{-- Fallback for non-array string --}}
                                                    <span class="badge bg-success text-white">{{ $patient->medications_used }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.notes') }}</th>
                                            <td>{{ $patient->notes ?: '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 