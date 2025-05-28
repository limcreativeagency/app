@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h4 class="mb-3">Okunmamış Mesajlar</h4>
    @if($groups->isEmpty())
        <div class="alert alert-info">Okunmamış mesaj yok.</div>
    @else
        <div class="row g-4">
            @foreach($groups as $group)
                <div class="col-12 col-sm-6 col-md-4">
                    <a href="{{ route('treatments.messages', $group['treatment_id']) }}" class="text-decoration-none text-dark">
                        <div class="card shadow-sm border-0 h-100 unread-card" style="background:#e6f4ea; transition:background 0.2s;">
                            <div class="card-body d-flex flex-column justify-content-between h-100">
                                <div>
                                    <div class="fw-bold text-dark mb-1">{{ $group['patient_name'] }}</div>
                                    <div class="text-muted small mb-2">{{ $group['treatment_title'] }}</div>
                                </div>
                                <div class="d-flex justify-content-between align-items-end mt-auto">
                                    <small class="text-muted">
                                        @php
                                            $dt = \Carbon\Carbon::parse($group['last_message_at']);
                                            if ($dt->isToday()) {
                                                echo $dt->format('H:i');
                                            } elseif ($dt->isYesterday()) {
                                                echo 'Dün';
                                            } else {
                                                echo $dt->format('d.m.Y');
                                            }
                                        @endphp
                                    </small>
                                    <span class="badge bg-danger ms-2">
                                        {{ $group['unread_count'] }} Yeni Mesaj
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.unread-card:hover {
    background: #d4ecd8 !important;
}
</style>
@endpush 