@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Hasta Tedavileri</h4>
        @if(auth()->user()->hasRole(['admin', 'doctor']))
        <a href="{{ route('treatments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Yeni Tedavi Ekle
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tedavi İstatistikleri -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Toplam Tedavi</h6>
                    <h2 class="mb-0">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Tamamlanan</h6>
                    <h2 class="mb-0">{{ $stats['completed'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Devam Eden</h6>
                    <h2 class="mb-0">{{ $stats['in_progress'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white">
        <div class="card-body">
                    <h6 class="card-title">Planlanan</h6>
                    <h2 class="mb-0">{{ $stats['planned'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Hasta</th>
                                    <th>Tedavi Başlığı</th>
                                    <th>Başlangıç</th>
                                    <th>Bitiş</th>
                                    <th>Durum</th>
                                    <th>Aşamalar</th>
                                    <th>Fotoğraflar</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($treatments as $treatment)
                                    <tr>
                                        <td>{{  $treatment->patient->user->name }}</td>
                                        <td>{{ $treatment->title }}</td>
                                        <td>{{ $treatment->start_date->format('d.m.Y') }}</td>
                                        <td>{{ $treatment->end_date ? $treatment->end_date->format('d.m.Y') : '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $treatment->status === 'completed' ? 'success' : ($treatment->status === 'in_progress' ? 'primary' : ($treatment->status === 'cancelled' ? 'danger' : 'secondary')) }}">
                                                {{ __('treatments.status.' . $treatment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $treatment->stages->count() }} Aşama
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $treatment->photos->count() }} Fotoğraf
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('treatments.show', $treatment) }}" class="btn btn-sm btn-info" title="Görüntüle">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if(auth()->user()->hasRole(['admin', 'doctor']))
                                                <a href="{{ route('treatments.edit', $treatment) }}" class="btn btn-sm btn-warning" title="Düzenle">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('treatments.destroy', $treatment) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu tedaviyi silmek istediğinizden emin misiniz?')" title="Sil">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-clipboard-list fs-2 mb-3 d-block"></i>
                                                <h6>Henüz Tedavi Kaydı Bulunmuyor</h6>
                                                <p class="mb-0">
                                                    @if(auth()->user()->hasRole(['admin', 'doctor']))
                                                    Yeni bir tedavi eklemek için yukarıdaki "Yeni Tedavi Ekle" butonunu kullanabilirsiniz.
                                                    @else
                                                    Henüz size atanmış bir tedavi bulunmamaktadır.
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
            </div>
            
                    @if($treatments->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $treatments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .avatar {
        width: 32px;
        height: 32px;
    }
    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }
</style>
@endpush
@endsection 