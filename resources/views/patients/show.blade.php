@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('patients.patient_details') }}</h3>
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
                                                        {{ __('patients.gender_male') }}
                                                        @break
                                                    @case('female')
                                                        {{ __('patients.gender_female') }}
                                                        @break
                                                    @case('other')
                                                        {{ __('patients.gender_other') }}
                                                        @break
                                                    @default
                                                        -
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.marital_status') }}</th>
                                            <td>
                                                @switch($patient->marital_status)
                                                    @case('single')
                                                        {{ __('patients.single') }}
                                                        @break
                                                    @case('married')
                                                        {{ __('patients.married') }}
                                                        @break
                                                    @case('divorced')
                                                        {{ __('patients.divorced') }}
                                                        @break
                                                    @case('widowed')
                                                        {{ __('patients.widowed') }}
                                                        @break
                                                    @default
                                                        -
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.occupation') }}</th>
                                            <td>{{ $patient->occupation ?: '-' }}</td>
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
                                            <th style="width: 40%">{{ __('patients.height') }}</th>
                                            <td>{{ $patient->height ? $patient->height . ' cm' : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.weight') }}</th>
                                            <td>{{ $patient->weight ? $patient->weight . ' kg' : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.bmi') }}</th>
                                            <td>{{ $patient->height && $patient->weight ? number_format($patient->weight / (($patient->height/100) * ($patient->height/100)), 2) : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.blood_type') }}</th>
                                            <td>{{ $patient->blood_type ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.smoking_status') }}</th>
                                            <td>
                                                @switch($patient->smoking_status)
                                                    @case('never')
                                                        {{ __('patients.never_smoked') }}
                                                        @break
                                                    @case('former')
                                                        {{ __('patients.former_smoker') }}
                                                        @break
                                                    @case('current')
                                                        {{ __('patients.current_smoker') }}
                                                        @break
                                                    @default
                                                        -
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.alcohol_consumption') }}</th>
                                            <td>
                                                @switch($patient->alcohol_consumption)
                                                    @case('never')
                                                        {{ __('patients.never_drinks') }}
                                                        @break
                                                    @case('occasional')
                                                        {{ __('patients.occasional_drinker') }}
                                                        @break
                                                    @case('regular')
                                                        {{ __('patients.regular_drinker') }}
                                                        @break
                                                    @case('former')
                                                        {{ __('patients.former_drinker') }}
                                                        @break
                                                    @default
                                                        -
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.exercise_status') }}</th>
                                            <td>
                                                @switch($patient->exercise_status)
                                                    @case('none')
                                                        {{ __('patients.no_exercise') }}
                                                        @break
                                                    @case('occasional')
                                                        {{ __('patients.occasional_exercise') }}
                                                        @break
                                                    @case('regular')
                                                        {{ __('patients.regular_exercise') }}
                                                        @break
                                                    @default
                                                        -
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.medical_history') }}</th>
                                            <td>{{ $patient->medical_history ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.allergies') }}</th>
                                            <td>
                                                @php
                                                    $allergies = $patient->allergies;
                                                    if (is_string($allergies)) {
                                                        $decoded = json_decode($allergies, true);
                                                        $allergies = $decoded !== null ? $decoded : $allergies;
                                                    }
                                                @endphp
                                                @if(!empty($allergies) && is_array($allergies))
                                                    @foreach($allergies as $allergy)
                                                        <span class="badge bg-info text-dark me-1 mb-1">{{ is_array($allergy) && isset($allergy['value']) ? $allergy['value'] : $allergy }}</span>
                                                    @endforeach
                                                @elseif(!empty($allergies))
                                                    <span class="badge bg-info text-dark">{{ $allergies }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.chronic_diseases') }}</th>
                                            <td>
                                                @php
                                                    $chronic = $patient->chronic_diseases;
                                                    if (is_string($chronic)) {
                                                        $decoded = json_decode($chronic, true);
                                                        $chronic = $decoded !== null ? $decoded : $chronic;
                                                    }
                                                @endphp
                                                @if(!empty($chronic) && is_array($chronic))
                                                    @foreach($chronic as $disease)
                                                        <span class="badge bg-warning text-dark me-1 mb-1">{{ is_array($disease) && isset($disease['value']) ? $disease['value'] : $disease }}</span>
                                                    @endforeach
                                                @elseif(!empty($chronic))
                                                    <span class="badge bg-warning text-dark">{{ $chronic }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.medications_used') }}</th>
                                            <td>
                                                @if($patient->medications_used)
                                                    @foreach($patient->medications_used as $medication)
                                                        <span class="badge bg-info me-1">{{ $medication }}</span>
                                                    @endforeach
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.dietary_habits') }}</th>
                                            <td>{{ $patient->dietary_habits ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('patients.notes') }}</th>
                                            <td>{{ $patient->notes ?: '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('patients.section_emergency_contacts') }}</h4>
                                </div>
                                <div class="card-body">
                                    @if($patient->emergencyContacts && $patient->emergencyContacts->count() > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('patients.contact_name') }}</th>
                                                    <th>{{ __('patients.contact_phone') }}</th>
                                                    <th>{{ __('patients.contact_relation') }}</th>
                                                    <th style="width: 100px">{{ __('general.actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($patient->emergencyContacts as $contact)
                                                    <tr>
                                                        <td>{{ $contact->name ?: '-' }}</td>
                                                        <td>{{ $contact->phone ?: '-' }}</td>
                                                        <td>{{ $contact->relation ?: '-' }}</td>
                                                        <td>
                                                            <form action="{{ route('emergency-contacts.destroy', $contact->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('patients.confirm_delete_contact') }}')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-muted mb-0">{{ __('patients.no_emergency_contacts') }}</p>
                                    @endif
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