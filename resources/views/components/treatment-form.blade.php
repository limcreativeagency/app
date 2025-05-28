@props(['presetPatient' => null, 'patients' => []])

<form action="{{ route('treatments.store') }}" method="POST" class="needs-validation" novalidate>
    @csrf
    
    <div class="card">
        <div class="card-body">
            <!-- Hasta Seçimi -->
            <div class="mb-4">
                @if($presetPatient)
                    <div class="alert alert-info">
                        <strong>Hasta:</strong> {{ $presetPatient->user->name }}
                        <input type="hidden" name="patient_id" value="{{ $presetPatient->id }}">
                    </div>
                @else
                    <label for="patient_id" class="form-label">Hasta Seçin <span class="text-danger">*</span></label>
                    <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">Hasta Seçin</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                @endif
            </div>

            <!-- Tedavi Başlığı ve Durumu -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="title" class="form-label">Tedavi Başlığı <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                        value="{{ old('title', 'Saç Ekimi') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status" class="form-label">Tedavi Durumu <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="planned" {{ old('status') == 'planned' ? 'selected' : '' }}>Planlandı</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>Devam Ediyor</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>İptal Edildi</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tedavi Yöntem -->
            <div class="row mb-4 align-items-start">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="treatment_type" class="form-label">Yöntem <span class="text-danger">*</span></label>
                        <select name="treatment_type" id="treatment_type" class="form-select @error('treatment_type') is-invalid @enderror">
                            <option value="">Seçiniz</option>
                            <option value="FUE" {{ old('treatment_type') == 'FUE' ? 'selected' : '' }}>FUE</option>
                            <option value="DHI" {{ old('treatment_type') == 'DHI' ? 'selected' : '' }}>DHI</option>
                            <option value="Sapphire FUE" {{ old('treatment_type') == 'Sapphire FUE' ? 'selected' : '' }}>Sapphire FUE</option>
                            <option value="Diğer" {{ old('treatment_type') == 'Diğer' ? 'selected' : '' }}>Diğer</option>
                        </select>
                        @error('treatment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Doktor ve Operasyon Tarihi Yanyana -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="user_id" class="form-label">Operasyonu Yapan Doktor <span class="text-danger">*</span></label>
                        <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror">
                            <option value="">Seçiniz</option>
                            @foreach(\App\Models\User::whereHas('role', function($q){ $q->where('slug', 'doctor'); })->get() as $doctor)
                                <option value="{{ $doctor->id }}" {{ (old('user_id', isset($treatment) ? $treatment->user_id : null) == $doctor->id) ? 'selected' : '' }}>{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="operation_date" class="form-label">Operasyon Tarihi</label>
                        <input type="date" name="operation_date" id="operation_date" class="form-control @error('operation_date') is-invalid @enderror" value="{{ old('operation_date') }}">
                        @error('operation_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Başlangıç ve Bitiş Tarihi -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Tedavi Başlangıç Tarihi <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" id="start_date" 
                        class="form-control @error('start_date') is-invalid @enderror"
                        value="{{ old('start_date', date('Y-m-d')) }}" required>
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">Tedavi Bitiş Tarihi</label>
                    <input type="date" name="end_date" id="end_date" 
                        class="form-control @error('end_date') is-invalid @enderror"
                        value="{{ old('end_date') }}">
                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Tedavi Durumu ve Ekim Alanı Yanyana -->
            <div class="row mb-4 align-items-start">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Ekim Alanı <span class="text-danger">*</span></label>
                        <div class="ekim-alani-checkboxes mt-2">
                            @php
                                $areas = ['Ön', 'Tepe', 'Yanlar', 'Diğer'];
                                $selectedAreas = collect(old('treatment_area', isset($treatment) ? $treatment->treatment_area : []));
                                if (is_string($selectedAreas)) {
                                    $selectedAreas = collect(json_decode($selectedAreas, true) ?? []);
                                }
                            @endphp
                            @foreach($areas as $area)
                                <div class="form-check form-check-inline ekim-alani-check">
                                    <input class="form-check-input" type="checkbox" name="treatment_area[]" id="area_{{ $area }}" value="{{ $area }}" {{ $selectedAreas->contains($area) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="area_{{ $area }}">{{ $area }}</label>
                                </div>
                            @endforeach
                        </div>
                        <small class="form-text text-muted">Birden fazla alan seçebilirsiniz.</small>
                        @error('treatment_area')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="mt-2">
                            @foreach($selectedAreas as $area)
                                <span class="badge bg-info text-dark">{{ $area }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="graft_count" class="form-label">Ekilen Greft Sayısı <span class="text-danger">*</span></label>
                        <select name="graft_count" id="graft_count" class="form-select @error('graft_count') is-invalid @enderror">
                            <option value="">Seçiniz</option>
                            <option value="1500-3000" {{ old('graft_count') == '1500-3000' ? 'selected' : '' }}>1500-3000 Greft</option>
                            <option value="3000-4500" {{ old('graft_count') == '3000-4500' ? 'selected' : '' }}>3000-4500 Greft</option>
                            <option value="4500-6000" {{ old('graft_count') == '4500-6000' ? 'selected' : '' }}>4500-6000 Greft</option>
                            <option value="6000-8000" {{ old('graft_count') == '6000-8000' ? 'selected' : '' }}>6000-8000 Greft</option>
                        </select>
                        @error('graft_count')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tedavi Açıklaması -->
            <div class="mb-4">
                <label for="description" class="form-label">Tedavi Açıklaması</label>
                <textarea name="description" id="description" rows="4" 
                    class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <!-- Notlar -->
            <div class="mb-4">
                <label for="notes" class="form-label">Notlar</label>
                <textarea name="notes" id="notes" rows="3" 
                    class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>        
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Tedavi Kaydet
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i> İptal
            </a>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bitiş tarihi, başlangıç tarihinden önce seçilemesin
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    startDate.addEventListener('change', function() {
        endDate.min = this.value;
        if (endDate.value && endDate.value < this.value) {
            endDate.value = this.value;
        }
    });

    // Başlangıç tarihi için minimum değer bugün olsun
    startDate.min = new Date().toISOString().split('T')[0];
});
</script>
@endpush

@push('styles')
<style>
.ekim-alani-checkboxes {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem 1.2rem;
    align-items: center;
}
.ekim-alani-check {
    margin-bottom: 0 !important;
}
.ekim-alani-check label {
    margin-left: 0.35rem;
    cursor: pointer;
}
</style>
@endpush 