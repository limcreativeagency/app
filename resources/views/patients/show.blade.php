@extends('layouts.app')

@push('styles')
<style>
    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 1rem;
    }
    .nav-tabs .nav-link {
        margin-bottom: -2px;
        border: none;
        border-bottom: 2px solid transparent;
        border-radius: 0;
        color: #6c757d;
        padding: 1rem 1.5rem;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }
    .nav-tabs .nav-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
        isolation: isolate;
        color: #0d6efd;
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom: 2px solid #0d6efd;
        background-color: transparent;
    }
    .nav-tabs .nav-link i {
        margin-right: 8px;
    }
    .tab-content {
        padding: 20px 0;
    }
    .tab-pane {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .badge {
        margin-right: 0.25rem;
        margin-bottom: 0.25rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">{{ __('patients.patient_details') }}</h5>
                    <div class="btn-group">
                        <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil"></i> Düzenle
                        </a>
                        <a href="{{ route('patients.index') }}" class="btn btn-secondary btn-sm ms-2">
                            <i class="bi bi-arrow-left"></i> Geri
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Tab Menüsü -->
                    <ul class="nav nav-tabs" id="patientTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                                <i class="bi bi-person-circle"></i> Kişisel Bilgiler
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="health-tab" data-bs-toggle="tab" data-bs-target="#health" type="button" role="tab">
                                <i class="bi bi-heart-pulse"></i> Sağlık Bilgileri
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="treatments-tab" data-bs-toggle="tab" data-bs-target="#treatments" type="button" role="tab">
                                <i class="bi bi-capsule"></i> Tedaviler ve İlaçlar
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="emergency-tab" data-bs-toggle="tab" data-bs-target="#emergency" type="button" role="tab">
                                <i class="bi bi-people-fill"></i> Acil Durum
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes" type="button" role="tab">
                                <i class="bi bi-journal-text"></i> Notlar
                            </button>
                        </li>
                    </ul>

                    <!-- Tab İçerikleri -->
                    <div class="tab-content" id="patientTabsContent">
                        <!-- Kişisel Bilgiler -->
                        <div class="tab-pane fade show active" id="personal" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 30%">{{ __('patients.name') }}</th>
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
                                    <tr>
                                        <th>{{ __('patients.address') }}</th>
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

                        <!-- Sağlık Bilgileri -->
                        <div class="tab-pane fade" id="health" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 30%">{{ __('patients.height') }}</th>
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
                                                    <span class="badge bg-info text-dark">{{ is_array($allergy) && isset($allergy['value']) ? $allergy['value'] : $allergy }}</span>
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
                                                    <span class="badge bg-warning text-dark">{{ is_array($disease) && isset($disease['value']) ? $disease['value'] : $disease }}</span>
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
                                            @php
                                                $medications = $patient->medications_used;
                                                if (is_string($medications)) {
                                                    $decoded = json_decode($medications, true);
                                                    $medications = $decoded !== null ? $decoded : $medications;
                                                }
                                            @endphp
                                            @if(!empty($medications) && is_array($medications))
                                                @foreach($medications as $medication)
                                                    <span class="badge bg-success text-light">{{ is_array($medication) && isset($medication['value']) ? $medication['value'] : $medication }}</span>
                                                @endforeach
                                            @elseif(!empty($medications))
                                                <span class="badge bg-success text-light">{{ $medications }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('patients.dietary_habits') }}</th>
                                        <td>{{ $patient->dietary_habits ?: '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Tedaviler -->
                        <div class="tab-pane fade" id="treatments" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0">Tedaviler</h5>
                                <a href="{{ route('treatments.create', ['patient_id' => $patient->id]) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus"></i> Yeni Tedavi Ekle
                                </a>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tedavi Adı</th>
                                            <th>Başlangıç</th>
                                            <th>Bitiş</th>
                                            <th>Durum</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($patient->treatments ?? []) as $treatment)
                                        <tr>
                                            <td>{{ $treatment->title }}</td>
                                            <td>{{ $treatment->start_date->format('d.m.Y') }}</td>
                                            <td>{{ $treatment->end_date ? $treatment->end_date->format('d.m.Y') : '-' }}</td>
                                            <td>
                                                @switch($treatment->status)
                                                    @case('planned')
                                                        <span class="badge bg-info">Planlandı</span>
                                                        @break
                                                    @case('in_progress')
                                                        <span class="badge bg-primary">Devam Ediyor</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-success">Tamamlandı</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-danger">İptal Edildi</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('treatments.show', $treatment->id) }}" class="btn btn-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('treatments.edit', $treatment->id) }}" class="btn btn-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <p class="text-muted mb-0">Henüz tedavi kaydı bulunmamaktadır.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Acil Durum -->
                        <div class="tab-pane fade" id="emergency" role="tabpanel">
                            @if($patient->emergencyContacts && $patient->emergencyContacts->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('patients.contact_name') }}</th>
                                                <th>{{ __('patients.contact_phone') }}</th>
                                                <th>{{ __('patients.contact_relation') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($patient->emergencyContacts as $contact)
                                                <tr>
                                                    <td>{{ $contact->name ?: '-' }}</td>
                                                    <td>{{ $contact->phone ?: '-' }}</td>
                                                    <td>{{ $contact->relation ?: '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">{{ __('patients.no_emergency_contacts') }}</p>
                            @endif
                        </div>

                        <!-- Notlar -->
                        <div class="tab-pane fade" id="notes" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 30%">{{ __('patients.notes') }}</th>
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

@endsection

@push('scripts')
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tabs
        var triggerTabList = [].slice.call(document.querySelectorAll('#patientTabs button'));
        triggerTabList.forEach(function(triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl);
            triggerEl.addEventListener('click', function(event) {
                event.preventDefault();
                tabTrigger.show();
            });
        });

        // Get the hash value from the URL
        var hash = window.location.hash;
        if (hash) {
            // If there's a hash, activate the corresponding tab
            var activeTab = document.querySelector('#patientTabs button[data-bs-target="' + hash + '"]');
            if (activeTab) {
                var tab = new bootstrap.Tab(activeTab);
                tab.show();
            }
        }

        // Update URL hash when tab changes
        var tabs = document.querySelectorAll('#patientTabs button');
        tabs.forEach(function(tab) {
            tab.addEventListener('shown.bs.tab', function(event) {
                window.location.hash = event.target.getAttribute('data-bs-target');
            });
        });
    });
</script>
@endpush 