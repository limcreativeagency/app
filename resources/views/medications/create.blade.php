@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Yeni İlaç Planı Ekle</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('medication-plans.store') }}" method="POST">
                @csrf
                <input type="hidden" name="treatment_id" value="{{ $treatment->id }}">
                <div class="mb-3">
                    <label for="name" class="form-label">İlaç Adı</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">İlaç Türü</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="oral">Oral</option>
                        <option value="topical">Topikal</option>
                        <option value="spray">Sprey</option>
                        <option value="vitamin">Vitamin</option>
                    </select>
                    @error('type')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="dose" class="form-label">Doz</label>
                    <input type="text" name="dose" id="dose" class="form-control" value="{{ old('dose', '1x1') }}" required>
                    <input type="hidden" id="original_dose" value="{{ old('dose', '1x1') }}">
                    <small class="text-muted">Doz otomatik olarak ilaç alım saatlerinin sayısına göre güncellenir</small>
                    @error('dose')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="instructions" class="form-label">Kullanım Talimatları</label>
                    <textarea name="instructions" id="instructions" class="form-control">{{ old('instructions') }}</textarea>
                    @error('instructions')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Kullanım Saatleri</label>
                    <div id="times-container">
                        <div class="input-group mb-2 time-input-group">
                            <input type="text" name="times[]" class="form-control time-picker" placeholder="--:--" required>
                            <button type="button" class="btn btn-danger remove-time">-</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" id="add-time">Saat Ekle</button>
                    @error('times')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                    <input type="text" name="start_date" id="start_date" class="form-control date-picker" placeholder="gg.aa.yyyy" value="{{ old('start_date') }}" required>
                    @error('start_date')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">Bitiş Tarihi</label>
                    <input type="text" name="end_date" id="end_date" class="form-control date-picker" placeholder="gg.aa.yyyy" value="{{ old('end_date') }}" required>
                    @error('end_date')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Notlar</label>
                    <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
                    @error('notes')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-success">Kaydet</button>
                <a href="{{ route('treatments.show', $treatment->id) }}" class="btn btn-secondary">İptal</a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
$(function() {
    function initTimePickers() {
        $("input.time-picker").each(function() {
            if (!$(this).hasClass('flatpickr-applied')) {
                flatpickr(this, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    onClose: function(selectedDates, dateStr, instance) {
                        $(instance.input).trigger('change');
                    }
                });
                $(this).addClass('flatpickr-applied');
            }
        });
    }
    function updateDose() {
        const timeCount = $('.time-input-group').length;
        const originalDose = $('#original_dose').val();
        const doseMatch = originalDose.match(/^(\d+)x(\d+)$/);
        if (doseMatch) {
            $('#dose').val(timeCount + 'x' + doseMatch[2]);
        } else {
            $('#dose').val(timeCount + 'x1');
        }
    }
    initTimePickers();
    $('#add-time').on('click', function() {
        $('#times-container').append('<div class="input-group mb-2 time-input-group"><input type="text" name="times[]" class="form-control time-picker" placeholder="--:--" required><button type="button" class="btn btn-danger remove-time">-</button></div>');
        initTimePickers();
        updateDose();
    });
    $(document).on('click', '.remove-time', function() {
        if ($('.time-input-group').length > 1) {
            $(this).closest('.time-input-group').remove();
            updateDose();
        }
    });
    // Tarih inputları için flatpickr
    $("input.date-picker").flatpickr({
        dateFormat: "d.m.Y",
        allowInput: true
    });
    // Başlangıç tarihi seçilince bitiş tarihine odaklan ve aç
    $('#start_date').on('change', function() {
        setTimeout(function() {
            $('#end_date').focus();
            $('#end_date')[0]._flatpickr.open();
        }, 100);
    });
    // Sayfa yüklendiğinde dozu güncelle
    updateDose();
});
</script>
@endpush 