@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ __('Hastalar') }}</h4>
        <a href="{{ route('patients.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i> {{ __('Yeni Hasta') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 px-3 small mb-2">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger py-2 px-3 small mb-2">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-sm table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px;">#</th>
                        <th style="width:40px;"></th>
                        <th>Ad Soyad</th>
                        <th>Telefon</th>
                        <th style="width:80px;">Durum</th>
                        <th style="width:110px;" class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                        <tr>
                            <td>{{ $patient->id }}</td>
                            <td>
                                @if($patient->profile_photo)
                                    <img src="{{ Storage::url($patient->profile_photo) }}" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;">
                                @else
                                    <span class="badge bg-secondary rounded-circle" style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;">
                                        {{ strtoupper(substr($patient->user->name, 0, 1)) }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $patient->user->name }}</span>
                                <div class="text-muted small">{{ $patient->user->email }}</div>
                            </td>
                            <td class="small">{{ $patient->user->phone }}</td>
                            <td>
                                @if($patient->user->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pasif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-outline-primary" title="Görüntüle"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-outline-secondary" title="Düzenle"><i class="bi bi-pencil"></i></a>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Sil"
                                    onclick="confirmDelete('{{ route('patients.destroy', $patient) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-people fs-2 d-block mb-2"></i>
                                Henüz hasta kaydı bulunmuyor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($patients->hasPages())
            <div class="card-footer py-2">
                <div class="d-flex justify-content-center">
                    {{ $patients->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(url) {
    if (confirm('Bu hastayı silmek istediğinizden emin misiniz?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.innerHTML = `@csrf @method('DELETE')`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection 