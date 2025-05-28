@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tedavi Düzenle</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('treatments.update', $treatment) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Hasta</label>
                            <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                <option value="">Hasta Seçin</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ (old('patient_id', $treatment->patient_id) == $patient->id) ? 'selected' : '' }}>
                                        {{ $patient->user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Tedavi Başlığı</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                        id="title" name="title" value="{{ old('title', $treatment->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Tedavi Durumu</label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="">Seçiniz</option>
                                        <option value="planned" {{ old('status', $treatment->status) == 'planned' ? 'selected' : '' }}>Planlandı</option>
                                        <option value="in_progress" {{ old('status', $treatment->status) == 'in_progress' ? 'selected' : '' }}>Devam Ediyor</option>
                                        <option value="completed" {{ old('status', $treatment->status) == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                                        <option value="cancelled" {{ old('status', $treatment->status) == 'cancelled' ? 'selected' : '' }}>İptal Edildi</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Operasyonu Yapan Doktor</label>
                            <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">Seçiniz</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('user_id', $treatment->user_id) == $doctor->id ? 'selected' : '' }}>{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="treatment_type" class="form-label">Yöntem</label>
                            <select name="treatment_type" id="treatment_type" class="form-select @error('treatment_type') is-invalid @enderror" required>
                                <option value="">Seçiniz</option>
                                <option value="FUE" {{ old('treatment_type', $treatment->treatment_type) == 'FUE' ? 'selected' : '' }}>FUE</option>
                                <option value="DHI" {{ old('treatment_type', $treatment->treatment_type) == 'DHI' ? 'selected' : '' }}>DHI</option>
                                <option value="Sapphire FUE" {{ old('treatment_type', $treatment->treatment_type) == 'Sapphire FUE' ? 'selected' : '' }}>Sapphire FUE</option>
                                <option value="Diğer" {{ old('treatment_type', $treatment->treatment_type) == 'Diğer' ? 'selected' : '' }}>Diğer</option>
                            </select>
                            @error('treatment_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ekim Alanı <span class="text-danger">*</span></label>
                            <div>
                                @php
                                    $areas = $treatment->treatment_area;
                                    if (is_string($areas)) {
                                        $areas = json_decode($areas, true) ?: [];
                                    }
                                    $selectedAreas = collect(old('treatment_area', $areas));
                                @endphp
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="treatment_area[]" id="alan_on" value="Ön" {{ $selectedAreas->contains('Ön') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="alan_on">Ön</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="treatment_area[]" id="alan_tepe" value="Tepe" {{ $selectedAreas->contains('Tepe') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="alan_tepe">Tepe</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="treatment_area[]" id="alan_yanlar" value="Yanlar" {{ $selectedAreas->contains('Yanlar') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="alan_yanlar">Yanlar</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="treatment_area[]" id="alan_diger" value="Diğer" {{ $selectedAreas->contains('Diğer') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="alan_diger">Diğer</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">Birden fazla alan seçebilirsiniz.</small>
                            @error('treatment_area')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="graft_count" class="form-label">Gref Sayısı <span class="text-danger">*</span></label>
                            <select name="graft_count" id="graft_count" class="form-select @error('graft_count') is-invalid @enderror" required>
                                <option value="">Seçiniz</option>
                                <option value="1500-3000" {{ old('graft_count', $treatment->graft_count) == '1500-3000' ? 'selected' : '' }}>1500-3000 Greft</option>
                                <option value="3000-4500" {{ old('graft_count', $treatment->graft_count) == '3000-4500' ? 'selected' : '' }}>3000-4500 Greft</option>
                                <option value="4500-6000" {{ old('graft_count', $treatment->graft_count) == '4500-6000' ? 'selected' : '' }}>4500-6000 Greft</option>
                                <option value="6000-8000" {{ old('graft_count', $treatment->graft_count) == '6000-8000' ? 'selected' : '' }}>6000-8000 Greft</option>
                            </select>
                            @error('graft_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Tedavi Başlangıç Tarihi</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                        id="start_date" name="start_date" value="{{ old('start_date', $treatment->start_date->format('Y-m-d')) }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Tedavi Bitiş Tarihi</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                        id="end_date" name="end_date" value="{{ old('end_date', $treatment->end_date ? $treatment->end_date->format('Y-m-d') : '') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="operation_date" class="form-label">Operasyon Tarihi</label>
                            <input type="date" name="operation_date" id="operation_date" class="form-control @error('operation_date') is-invalid @enderror" value="{{ old('operation_date', $treatment->operation_date ? $treatment->operation_date->format('Y-m-d') : '') }}" required>
                            @error('operation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="3">{{ old('description', $treatment->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notlar</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                id="notes" name="notes" rows="3">{{ old('notes', $treatment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('treatments.show', $treatment) }}" class="btn btn-secondary">Geri</a>
                            <button type="submit" class="btn btn-primary">Güncelle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 