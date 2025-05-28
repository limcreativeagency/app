@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ $treatment->title }} - Fotoğraf Yükle</h5>
                </div>
                <div class="card-body">
                    @if (
                        \Session::has('error') || $errors->any()
                    )
                        <div class="alert alert-danger">
                            @if(\Session::has('error'))
                                <div>{{ \Session::get('error') }}</div>
                            @endif
                            <ul>
                                @foreach (
                                    $errors->all() as $error
                                )
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('photos.store', $treatment->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="photo" class="form-label">Fotoğraf</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                   id="photo" name="photo" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif"
                                   required>
                            <div class="form-text">İzin verilen formatlar: JPEG, PNG, JPG, GIF. Maksimum boyut: 8MB</div>
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="photo_date" class="form-label">Fotoğraf Çekim Tarihi</label>
                            <input type="date" class="form-control @error('photo_date') is-invalid @enderror" id="photo_date" name="photo_date" value="{{ old('photo_date', date('Y-m-d')) }}" required>
                            @error('photo_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('treatments.show', $treatment) }}" class="btn btn-secondary">Geri</a>
                            <button type="submit" class="btn btn-primary">Fotoğraf Yükle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 