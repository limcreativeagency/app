@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">İlaç Düzenle</h5>
                </div>
                <div class="card-body">
                    @php
                        function safeDateFormat($date) {
                            try {
                                return \Carbon\Carbon::parse($date)->format('d.m.Y');
                            } catch (\Exception $e) {
                                return $date;
                            }
                        }
                    @endphp
                    <form action="{{ route('medication-plans.update', $plan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">İlaç Adı</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $plan->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">İlaç Türü</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="oral" {{ $plan->type == 'oral' ? 'selected' : '' }}>Oral</option>
                                <option value="topical" {{ $plan->type == 'topical' ? 'selected' : '' }}>Topikal</option>
                                <option value="spray" {{ $plan->type == 'spray' ? 'selected' : '' }}>Spray</option>
                                <option value="vitamin" {{ $plan->type == 'vitamin' ? 'selected' : '' }}>Vitamin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                            <input type="text" class="form-control date-picker" id="start_date" name="start_date" value="{{ old('start_date', safeDateFormat($plan->start_date)) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Bitiş Tarihi</label>
                            <input type="text" class="form-control date-picker" id="end_date" name="end_date" value="{{ old('end_date', safeDateFormat($plan->end_date)) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="dose" class="form-label">Doz</label>
                            <input type="text" class="form-control" id="dose" name="dose" value="{{ $plan->dose }}" required>
                            <input type="hidden" id="original_dose" value="{{ $plan->dose }}">
                            <small class="text-muted">Doz otomatik olarak ilaç alım saatlerinin sayısına göre güncellenir</small>
                        </div>
                        <div class="mb-3">
                            <label for="instructions" class="form-label">Kullanım Talimatları</label>
                            <textarea name="instructions" id="instructions" class="form-control">{{ old('instructions', $plan->instructions) }}</textarea>
                            @error('instructions')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">İlaç Alım Saatleri</label>
                            <div id="times-container">
                                @if(is_array($plan->times))
                                    @foreach($plan->times as $time)
                                    <div class="input-group mb-2 time-input-group">
                                        <input type="text" class="form-control time-picker" name="times[]" value="{{ $time }}" required>
                                        <button type="button" class="btn btn-danger remove-time">Sil</button>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2 time-input-group">
                                        <input type="text" class="form-control time-picker" name="times[]" value="08:00" required>
                                        <button type="button" class="btn btn-danger remove-time">Sil</button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-success" id="add-time">Saat Ekle</button>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notlar</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ $plan->notes }}</textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('treatments.show', $plan->treatment_id) }}" class="btn btn-secondary">Geri</a>
                            <button type="submit" class="btn btn-primary">Güncelle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<!-- Önce jQuery'yi yükle -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Sonra Flatpickr'ı yükle -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
// jQuery'nin yüklendiğinden emin ol
$(document).ready(function() {
    function initTimePickers() {
        $('.time-picker').each(function() {
            if (!this._flatpickr) {
                flatpickr(this, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    allowInput: true
                });
            }
        });
    }

    function initDatePickers() {
        $('.date-picker').each(function() {
            if (!this._flatpickr) {
                flatpickr(this, {
                    dateFormat: "d.m.Y",
                    allowInput: true
                });
            }
        });
    }

    // İlk yüklemede picker'ları başlat
    initTimePickers();
    initDatePickers();

    function updateDose() {
        const timeCount = $('.time-input-group').length;
        const originalDose = $('#original_dose').val();
        const doseMatch = originalDose.match(/^(\d+)x(\d+)$/);
        
        if (doseMatch) {
            // Eğer orijinal doz "3x1" formatındaysa, sadece ilk sayıyı güncelle
            $('#dose').val(timeCount + 'x' + doseMatch[2]);
        } else {
            // Eğer farklı bir format varsa, varsayılan olarak "3x1" formatını kullan
            $('#dose').val(timeCount + 'x1');
        }
    }

    // Saat ekle butonu için event listener
    $('#add-time').on('click', function() {
        $('#times-container').append(
            '<div class="input-group mb-2 time-input-group">' +
            '<input type="text" name="times[]" class="form-control time-picker" required>' +
            '<button type="button" class="btn btn-danger remove-time">-</button>' +
            '</div>'
        );
        initTimePickers();
        updateDose();
    });

    // Saat silme butonu için event listener
    $(document).on('click', '.remove-time', function() {
        if ($('.time-input-group').length > 1) {
            $(this).closest('.time-input-group').remove();
            updateDose();
        }
    });

    // Saat inputuna tıklayınca picker otomatik açılsın
    $(document).on('focus', '.time-picker', function() {
        if (this._flatpickr) {
            this._flatpickr.open();
        }
    });

    // Tarih inputuna tıklayınca picker otomatik açılsın
    $(document).on('focus', '.date-picker', function() {
        if (this._flatpickr) {
            this._flatpickr.open();
        }
    });

    // Başlangıç tarihi seçilince bitiş tarihine odaklan ve aç
    $('#start_date').on('change', function() {
        setTimeout(function() {
            $('#end_date').focus();
            if ($('#end_date')[0]._flatpickr) {
                $('#end_date')[0]._flatpickr.open();
            }
        }, 100);
    });

    // Sayfa yüklendiğinde dozu güncelle
    updateDose();
});
</script>
@endpush 