@php($treatment = $treatment)
<div class="modal fade" id="uploadPhotoModal" tabindex="-1" aria-labelledby="uploadPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadPhotoModalLabel">Fotoğraf Yükle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('photos.store', $treatment->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="photo" class="form-label">Fotoğraf</label>
                        <input type="file" class="form-control" id="photo" name="photo" required>
                    </div>
                    <div class="mb-3">
                        <label for="photo_date" class="form-label">Fotoğraf Tarihi</label>
                        <input type="date" class="form-control" id="photo_date" name="photo_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="photo_type" class="form-label">Fotoğraf Tipi</label>
                        <select class="form-select" id="photo_type" name="photo_type" required>
                            <option value="preop">Operasyon Öncesi</option>
                            <option value="postop">Operasyon Sonrası</option>
                            <option value="followup">Kontrol</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="photo_category" class="form-label">Görünüm Kategorisi</label>
                        <select class="form-select" id="photo_category" name="photo_category" required>
                            <option value="front">Ön Görünüm</option>
                            <option value="side">Yan Görünüm</option>
                            <option value="back">Arka Görünüm</option>
                            <option value="detail">Detay Görünüm</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="photo_angle" class="form-label">Açı</label>
                        <select class="form-select" id="photo_angle" name="photo_angle" required>
                            <option value="left">Sol</option>
                            <option value="right">Sağ</option>
                            <option value="front">Ön</option>
                            <option value="back">Arka</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Açıklama</label>
                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="photo_notes" class="form-label">Notlar</label>
                        <textarea class="form-control" id="photo_notes" name="photo_notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-primary">Yükle</button>
                </div>
            </form>
        </div>
    </div>
</div> 