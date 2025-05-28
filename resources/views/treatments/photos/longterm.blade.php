@extends('layouts.app')
@section('content')
<div class="container">
    <h4>{{ $treatment->title }} - 1. Yıl ve Sonrası Fotoğraflar</h4>
    <div class="row">
        @forelse($photos as $photo)
            <div class="col-md-4 mb-3">
                <img src="{{ asset('storage/' . $photo->image_path) }}" class="img-fluid rounded">
                @if($photo->stage_title)
                    <div>{{ $photo->stage_title }}</div>
                @endif
                <small>{{ $photo->created_at->format('d.m.Y H:i') }}</small>
            </div>
        @empty
            <div class="col-12 text-muted">1 yıl sonrası için fotoğraf yok.</div>
        @endforelse
    </div>
    <a href="{{ route('treatments.show', $treatment) }}" class="btn btn-secondary mt-3">Geri</a>
</div>
@endsection 