@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">{{ $treatment->title }}</h4>
                <div>
                    <a href="#notes" class="btn btn-outline-secondary btn-sm me-2">Notlara Git</a>
                    <a href="{{ route('treatments.edit', $treatment) }}" class="btn btn-primary btn-sm">Düzenle</a>
                    <form action="{{ route('treatments.destroy', $treatment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Emin misiniz?')">Sil</button>
                    </form>
                </div>
            </div>

            <!-- Tedavi Bilgileri -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Tedavi Bilgileri</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 200px;">Başlık</th>
                                <td>{{ $treatment->title }}</td>
                                <th style="width: 200px;">Doktor</th>
                                <td>{{ $treatment->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Hasta</th>
                                <td>{{ $treatment->patient->user->name }}</td>
                                <th>Durum</th>
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
                            </tr>
                            <tr>
                                <th>Operasyon Tarihi</th>
                                <td>{{ $treatment->operation_date ? $treatment->operation_date->format('d.m.Y') : 'Belirtilmedi' }}</td>
                                <th>Yöntem</th>
                                <td>{{ $treatment->treatment_type }}</td>
                            </tr>
                            <tr>
                                <th>Tedavi Başlangıç Tarihi</th>
                                <td>{{ $treatment->start_date->format('d.m.Y') }}</td>
                                <th>Ekim Alanı</th>
                                <td>
                                    @php
                                        $areas = $treatment->treatment_area;
                                        if (is_string($areas)) {
                                            $decoded = json_decode($areas, true);
                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                $areas = $decoded;
                                            }
                                        }
                                    @endphp
                                    @if(is_array($areas))
                                        @foreach($areas as $area)
                                            <span class="badge bg-info text-dark">{{ $area }}</span>
                                        @endforeach
                                    @elseif($areas)
                                        <span class="badge bg-info text-dark">{{ $areas }}</span>
                                    @else
                                        <span class="text-muted">Belirtilmedi</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tedavi Bitiş Tarihi</th>
                                <td>{{ $treatment->end_date ? $treatment->end_date->format('d.m.Y') : 'Belirtilmedi' }}</td>
                                <th>Gref Sayısı</th>
                                <td>{{ $treatment->graft_count ?? 'Belirtilmedi' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    @if($treatment->description)
                        <table class="table table-bordered mt-3">
                            <tbody>
                                <tr>
                                    <th style="width: 200px;">Açıklama</th>
                                    <td>{{ $treatment->description }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- İlaçlar -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">İlaçlar</h5>
                    <a href="{{ route('medication-plans.create', ['treatment' => $treatment->id]) }}" class="btn btn-primary btn-sm">Yeni İlaç Ekle</a>
                </div>
                <div class="card-body">
                    @if($treatment->medicationPlans && $treatment->medicationPlans->count())
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>İlaç Adı</th>
                                    <th>Başlangıç</th>
                                    <th>Bitiş</th>
                                    <th>Doz</th>
                                    <th>Açıklama</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($treatment->medicationPlans as $plan)
                                    @php
                                        try {
                                            $startFormatted = \Carbon\Carbon::parse($plan->start_date)->format('d.m.Y');
                                        } catch (\Exception $e) {
                                            $startFormatted = $plan->start_date;
                                        }
                                        try {
                                            $endFormatted = $plan->end_date ? \Carbon\Carbon::parse($plan->end_date)->format('d.m.Y') : 'Belirtilmedi';
                                        } catch (\Exception $e) {
                                            $endFormatted = $plan->end_date;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $plan->name }}</td>
                                        <td>{{ $startFormatted }}</td>
                                        <td>{{ $endFormatted }}</td>
                                        <td>{{ $plan->dose }}</td>
                                        <td>{{ $plan->description }}</td>
                                        <td>
                                            <a href="{{ route('medication-plans.edit', $plan) }}" class="btn btn-primary btn-sm">Düzenle</a>
                                            <form action="{{ route('medication-plans.destroy', $plan) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Emin misiniz?')">Sil</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Henüz ilaç eklenmemiş.</p>
                    @endif
                </div>
            </div>

            <!-- Fotoğraflar -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Fotoğraflar</h5>
                    <a href="{{ route('photos.create', $treatment->id) }}" class="btn btn-primary btn-sm">Yeni Fotoğraf Ekle</a>
                </div>
                <div class="card-body">
                    @if($treatment->photos && $treatment->photos->count())
                        <div class="row">
                            @foreach($treatment->photos as $photo)
                                <div class="col-md-4 mb-3">
                                    <img src="{{ asset('storage/' . $photo->image_path) }}" class="img-fluid" alt="Fotoğraf">
                                    <p class="mt-2">{{ $photo->created_at->format('d.m.Y') }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>Henüz fotoğraf eklenmemiş.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Notlar Bölümü -->
    <div class="card mt-4" id="notes">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ __('Notlar') }}</h5>
        </div>
        <div class="card-body">
            @auth
                <!-- Yeni Not Ekleme Formu -->
                <form action="{{ route('treatments.notes.store', $treatment) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="form-group">
                        <label for="note">{{ __('Yeni Not') }}</label>
                        <textarea name="note" id="note" rows="3" class="form-control @error('note') is-invalid @enderror" required></textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @php
                        $visibleToPatientRoles = ['doctor', 'admin', 'representative', 'clinic_admin'];
                    @endphp
                    @if(in_array(auth()->user()->role->slug ?? '', $visibleToPatientRoles))
                        <input type="hidden" name="is_visible_to_patient" value="0">
                        <div class="form-check mb-2">
                            <input type="checkbox" name="is_visible_to_patient" value="1" class="form-check-input" id="visibleCheck">
                            <label for="visibleCheck" class="form-check-label">Bu not hastaya görünsün</label>
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary">{{ __('Not Ekle') }}</button>
                </form>
            @endauth

            <!-- Tab Menü -->
            <ul class="nav nav-tabs mb-3" id="notesTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="patient-notes-tab" data-bs-toggle="tab" data-bs-target="#patient-notes" type="button" role="tab" aria-controls="patient-notes" aria-selected="true">Hasta Notları</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="clinic-notes-tab" data-bs-toggle="tab" data-bs-target="#clinic-notes" type="button" role="tab" aria-controls="clinic-notes" aria-selected="false">Klinik Notları</button>
                </li>
            </ul>

            <!-- Tab İçerikleri -->
            <div class="tab-content" id="notesTabContent">
                <!-- Hasta Notları (WhatsApp tarzı) -->
                <div class="tab-pane fade show active" id="patient-notes" role="tabpanel" aria-labelledby="patient-notes-tab">
                    @php
                        $unreadPatientMsg = \App\Models\TreatmentNote::where('treatment_id', $treatment->id)
                            ->where('user_id', '!=', auth()->id())
                            ->whereNull('read_at')
                            ->whereHas('user', function($q){ $q->whereHas('role', function($r){ $r->where('slug', 'patient'); }); })
                            ->where('is_visible_to_patient', true)
                            ->exists();
                    @endphp
                    @if($unreadPatientMsg)
                        <div class="alert alert-success text-center mb-2">{{ $treatment->patient->user->name }} yeni mesaj gönderdi</div>
                    @endif
                    <div class="d-flex justify-content-center mt-3">
                        <a href="{{ route('treatments.messages', $treatment) }}" class="btn btn-primary px-4 py-2">Mesajlaşma Ekranına Git</a>
                    </div>
                </div>

                <!-- Klinik Notları -->
                <div class="tab-pane fade" id="clinic-notes" role="tabpanel" aria-labelledby="clinic-notes-tab">
                    <div class="notes-list">
                        @php
                            $clinicNotes = $treatment->getVisibleNotes()->filter(function($note) {
                                return !$note->user->hasRole('patient');
                            });
                        @endphp
                        @forelse($clinicNotes as $note)
                            <div class="note-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">
                                            {{ $note->user->name }}
                                            <small class="text-muted">({{ __($note->user->role->slug) }})</small>
                                        </h6>
                                        <small class="text-muted">{{ $note->created_at->format('d.m.Y H:i') }}</small>
                                    </div>
                                    @if(auth()->id() === $note->user_id || auth()->user()->hasRole('admin'))
                                        <form action="{{ route('treatments.notes.destroy', $note) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Bu notu silmek istediğinizden emin misiniz?') }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <p class="mt-2 mb-2">{{ $note->note }}</p>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                {{ __('Henüz klinik notu bulunmuyor.') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .chat-area { max-height: 500px; overflow-y: auto; background: #fff; padding: 1rem; }
    .chat-bubble {
        max-width: 420px;
        border-radius: 1.25rem;
        padding: 0.5rem 1rem;
        font-size: 0.95rem;
        box-shadow: 0 1px 4px 0 rgba(0,0,0,0.04);
        margin-bottom: 0.5rem;
        display: flex;
        flex-direction: column;
    }
    .chat-bubble-right {
        background: #d1fae5;
        margin-left: auto;
        margin-right: 0;
        align-items: flex-end;
    }
    .chat-bubble-left {
        background: #f3f4f6;
        margin-right: auto;
        margin-left: 0;
        align-items: flex-start;
    }
    .chat-name {
        font-size: 0.85em;
        font-weight: 600;
        margin-bottom: 0.15em;
    }
    .chat-name-right { color: #059669; }
    .chat-name-left { color: #6d28d9; }
    .chat-date {
        font-size: 0.75em;
        color: #9ca3af;
        margin-top: 0.2em;
        align-self: flex-end;
    }
</style>
@endpush 