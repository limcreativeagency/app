@php($treatment = $treatment)
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Fotoğraflar</h5>
        <a href="{{ route('photos.create', $treatment->id) }}" class="btn btn-primary btn-sm">
            Fotoğraf Yükle
        </a>
    </div>
    <div class="card-body">
        <div id="photo-download-bar" class="mb-3" style="display:none;">
            <button id="download-selected" class="btn btn-success">
                <i class="bi bi-download"></i> Seçili Fotoğrafları İndir
            </button>
        </div>
        <ul class="nav nav-tabs mb-3" id="photoTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="preop-tab" data-bs-toggle="tab" data-bs-target="#preop" type="button" role="tab">Operasyon Öncesi</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="day-tab" data-bs-toggle="tab" data-bs-target="#day-group" type="button" role="tab">Günlük</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="month-tab" data-bs-toggle="tab" data-bs-target="#month" type="button" role="tab">Aylık</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="longterm-tab" data-bs-toggle="tab" data-bs-target="#longterm" type="button" role="tab">Uzun Dönem</button>
            </li>
        </ul>
        <div class="tab-content" id="photoTabsContent">
            <div class="tab-pane fade show active" id="preop" role="tabpanel">
                @include('treatments.partials.photos-sections', ['treatment' => $treatment, 'section' => 'preop'])
            </div>
            <div class="tab-pane fade" id="day-group" role="tabpanel">
                <ul class="nav nav-pills mb-3" id="daySubTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="day1-15-tab" data-bs-toggle="tab" data-bs-target="#day" type="button" role="tab">1-15. Gün</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="day16-30-tab" data-bs-toggle="tab" data-bs-target="#day2" type="button" role="tab">16-30. Gün</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="day" role="tabpanel">
                        @include('treatments.partials.photos-sections', ['treatment' => $treatment, 'section' => 'day'])
                    </div>
                    <div class="tab-pane fade" id="day2" role="tabpanel">
                        @include('treatments.partials.photos-sections', ['treatment' => $treatment, 'section' => 'day2'])
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="month" role="tabpanel">
                @include('treatments.partials.photos-sections', ['treatment' => $treatment, 'section' => 'month'])
            </div>
            <div class="tab-pane fade" id="longterm" role="tabpanel">
                @include('treatments.partials.photos-sections', ['treatment' => $treatment, 'section' => 'longterm'])
            </div>
        </div>
    </div>
</div>
@include('treatments.partials.photos-modal', ['treatment' => $treatment]) 