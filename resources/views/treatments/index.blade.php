@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tedaviler</h5>
                    <a href="{{ route('treatments.create') }}" class="btn btn-primary">Yeni Tedavi</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Hasta</th>
                                    <th>Doktor</th>
                                    <th>Başlık</th>
                                    <th>Başlangıç</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($treatments as $treatment)
                                    <tr>
                                        <td>{{ $treatment->id }}</td>
                                        <td>{{ $treatment->patient->user->name }}</td>
                                        <td>{{ $treatment->user->name }}</td>
                                        <td>{{ $treatment->title }}</td>
                                        <td>{{ $treatment->start_date->format('d.m.Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $treatment->status === 'completed' ? 'success' : ($treatment->status === 'in_progress' ? 'primary' : 'secondary') }}">
                                                {{ __('treatments.status.' . $treatment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('treatments.show', $treatment) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('treatments.edit', $treatment) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('treatments.destroy', $treatment) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu tedaviyi silmek istediğinizden emin misiniz?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Henüz tedavi kaydı bulunmamaktadır.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $treatments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 