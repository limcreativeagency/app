@push('styles')
<link href="https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/css/lightbox.min.css" rel="stylesheet">
<style>
.photo-card {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    border: 2px solid transparent;
    transition: border-color 0.2s;
}
.photo-card.selected {
    border-color: #0d6efd;
}
.photo-checkbox-custom {
    position: absolute;
    top: 8px;
    left: 8px;
    z-index: 2;
    background: #0d6efd;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}
.photo-checkbox-custom input[type=checkbox] {
    accent-color: #fff;
    width: 18px;
    height: 18px;
    margin: 0;
}
.photo-img-crop {
    width: 100%;
    aspect-ratio: 1/1;
    object-fit: cover;
    border-radius: 12px 12px 0 0;
    cursor: pointer;
}
.day-selector {
    display: flex;
    overflow-x: auto;
    gap: 4px;
    margin-bottom: 12px;
}
.day-btn {
    min-width: 90px;
    padding: 4px 8px;
    border: 1px solid #0d6efd;
    border-radius: 8px;
    background: #fff;
    color: #0d6efd;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
}
.day-btn.active, .day-btn:hover {
    background: #0d6efd;
    color: #fff;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/js/lightbox.min.js"></script>
<script>
$(document).ready(function() {
    // Checkbox ve seçili kart stili
    $(document).on('change', '.photo-checkbox', function() {
        var card = $(this).closest('.photo-card');
        if(this.checked) {
            card.addClass('selected');
        } else {
            card.removeClass('selected');
        }
        var checkedCount = $('.photo-checkbox:checked').length;
        if (checkedCount >= 1) {
            $('#photo-download-bar').show();
        } else {
            $('#photo-download-bar').hide();
        }
    });
    // Gün filtreleme (ilk 15 gün)
    $('.tab-pane#day .day-btn').on('click', function() {
        var day = $(this).data('day');
        $('.tab-pane#day .day-btn').removeClass('active');
        $(this).addClass('active');
        $('#day-photo-list .day-photo-item').hide();
        $('#day-photo-list .day-photo-item[data-day="'+day+'"]').show();
    });
    // Gün filtreleme (16-30 gün)
    $('.tab-pane#day2 .day-btn').on('click', function() {
        var day = $(this).data('day');
        $('.tab-pane#day2 .day-btn').removeClass('active');
        $(this).addClass('active');
        $('#day2-photo-list .day-photo-item').hide();
        $('#day2-photo-list .day-photo-item[data-day="'+day+'"]').show();
    });
    // ZIP indirme
    $('#download-selected').on('click', function() {
        var selectedIds = $('.photo-checkbox:checked').map(function() {
            return this.value;
        }).get();
        if (selectedIds.length >= 1) {
            var form = $('<form>', {
                'method': 'POST',
                'action': '{{ route("treatments.photos.downloadZip", $treatment->id) }}'
            });
            form.append($('<input>', {
                'type': 'hidden',
                'name': '_token',
                'value': '{{ csrf_token() }}'
            }));
            selectedIds.forEach(function(id) {
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'photo_ids[]',
                    'value': id
                }));
            });
            $('body').append(form);
            form.submit();
        }
    });
    // Lightbox2 otomatik çalışacak
});
</script>
@endpush

@php($treatment = $treatment)
<div class="tab-pane fade show active" id="preop" role="tabpanel">
    <div class="row">
        @forelse($treatment->photos->where('day_after_operation', '<', 0) as $photo)
        <div class="col-md-4 mb-3">
            <div class="photo-card" data-photo-id="{{ $photo->id }}">
                <label class="photo-checkbox-custom">
                    <input type="checkbox" class="photo-checkbox" value="{{ $photo->id }}" name="photo_ids[]">
                </label>
                <a href="{{ asset('storage/' . $photo->image_path) }}" data-lightbox="preop" data-title="{{ $photo->stage_title }}">
                    <img src="{{ asset('storage/' . $photo->image_path) }}" class="photo-img-crop" alt="Pre-op Photo">
                </a>
                <div class="card-body p-2">
                    <p class="card-text mb-1">{{ $photo->stage_title }}</p>
                    <small class="text-muted">{{ $photo->created_at->format('d.m.Y H:i') }}</small>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <p class="text-muted">Operasyon öncesi fotoğraf bulunmamaktadır.</p>
        </div>
        @endforelse
    </div>
</div>
<div class="tab-pane fade" id="day" role="tabpanel">
    <div class="day-selector mb-2">
        <button class="day-btn active" data-day="0">Operasyon Günü</button>
        @for($i=1; $i<=15; $i++)
            <button class="day-btn" data-day="{{ $i }}">{{ $i }}. Gün</button>
        @endfor
    </div>
    <div class="row" id="day-photo-list">
        @foreach($treatment->photos->where('day_after_operation', '>=', 0)->where('day_after_operation', '<', 15) as $photo)
        <div class="col-md-4 mb-3 day-photo-item" data-day="{{ $photo->day_after_operation }}">
            <div class="photo-card" data-photo-id="{{ $photo->id }}">
                <label class="photo-checkbox-custom">
                    <input type="checkbox" class="photo-checkbox" value="{{ $photo->id }}" name="photo_ids[]">
                </label>
                <a href="{{ asset('storage/' . $photo->image_path) }}" data-lightbox="day-{{ $photo->day_after_operation }}" data-title="{{ $photo->stage_title }}">
                    <img src="{{ asset('storage/' . $photo->image_path) }}" class="photo-img-crop" alt="Daily Photo">
                </a>
                <div class="card-body p-2">
                    <p class="card-text mb-1">{{ $photo->stage_title }}</p>
                    <small class="text-muted">{{ $photo->created_at->format('d.m.Y H:i') }}</small>
                </div>
            </div>
        </div>
        @endforeach
        @if($treatment->photos->where('day_after_operation', '>=', 0)->where('day_after_operation', '<', 15)->count() == 0)
        <div class="col-12">
            <p class="text-muted">1-15. günler için fotoğraf bulunmamaktadır.</p>
        </div>
        @endif
    </div>
</div>
<div class="tab-pane fade" id="day2" role="tabpanel">
    <div class="day-selector mb-2">
        @for($i=15; $i<30; $i++)
            <button class="day-btn" data-day="{{ $i }}">{{ $i }}. Gün</button>
        @endfor
    </div>
    <div class="row" id="day2-photo-list">
        @foreach($treatment->photos->where('day_after_operation', '>=', 15)->where('day_after_operation', '<', 30) as $photo)
        <div class="col-md-4 mb-3 day-photo-item" data-day="{{ $photo->day_after_operation }}">
            <div class="photo-card" data-photo-id="{{ $photo->id }}">
                <label class="photo-checkbox-custom">
                    <input type="checkbox" class="photo-checkbox" value="{{ $photo->id }}" name="photo_ids[]">
                </label>
                <a href="{{ asset('storage/' . $photo->image_path) }}" data-lightbox="day2-{{ $photo->day_after_operation }}" data-title="{{ $photo->stage_title }}">
                    <img src="{{ asset('storage/' . $photo->image_path) }}" class="photo-img-crop" alt="Daily Photo">
                </a>
                <div class="card-body p-2">
                    <p class="card-text mb-1">{{ $photo->stage_title }}</p>
                    <small class="text-muted">{{ $photo->created_at->format('d.m.Y H:i') }}</small>
                </div>
            </div>
        </div>
        @endforeach
        @if($treatment->photos->where('day_after_operation', '>=', 15)->where('day_after_operation', '<', 30)->count() == 0)
        <div class="col-12">
            <p class="text-muted">16-30. günler için fotoğraf bulunmamaktadır.</p>
        </div>
        @endif
    </div>
</div>
<div class="tab-pane fade" id="month" role="tabpanel">
    <div class="row">
        @forelse($treatment->photos->where('day_after_operation', '>=', 30)->where('day_after_operation', '<', 365) as $photo)
        <div class="col-md-4 mb-3">
            <div class="photo-card" data-photo-id="{{ $photo->id }}">
                <label class="photo-checkbox-custom">
                    <input type="checkbox" class="photo-checkbox" value="{{ $photo->id }}" name="photo_ids[]">
                </label>
                <a href="{{ asset('storage/' . $photo->image_path) }}" data-lightbox="month" data-title="{{ $photo->stage_title }}">
                    <img src="{{ asset('storage/' . $photo->image_path) }}" class="photo-img-crop" alt="Monthly Photo">
                </a>
                <div class="card-body p-2">
                    <p class="card-text mb-1">{{ $photo->stage_title }}</p>
                    <small class="text-muted">{{ $photo->created_at->format('d.m.Y H:i') }}</small>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <p class="text-muted">Aylık fotoğraf bulunmamaktadır.</p>
        </div>
        @endforelse
    </div>
</div>
<div class="tab-pane fade" id="longterm" role="tabpanel">
    <div class="row">
        @forelse($treatment->photos->where('day_after_operation', '>=', 365) as $photo)
        <div class="col-md-4 mb-3">
            <div class="photo-card" data-photo-id="{{ $photo->id }}">
                <label class="photo-checkbox-custom">
                    <input type="checkbox" class="photo-checkbox" value="{{ $photo->id }}" name="photo_ids[]">
                </label>
                <a href="{{ asset('storage/' . $photo->image_path) }}" data-lightbox="longterm" data-title="{{ $photo->stage_title }}">
                    <img src="{{ asset('storage/' . $photo->image_path) }}" class="photo-img-crop" alt="Long-term Photo">
                </a>
                <div class="card-body p-2">
                    <p class="card-text mb-1">{{ $photo->stage_title }}</p>
                    <small class="text-muted">{{ $photo->created_at->format('d.m.Y H:i') }}</small>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <p class="text-muted">Uzun dönem fotoğraf bulunmamaktadır.</p>
        </div>
        @endforelse
    </div>
</div> 