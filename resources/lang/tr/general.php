<?php

return [
    // Menü
    'menu' => [
        'home' => 'Ana Sayfa',
        'profile' => 'Profil',
        'settings' => 'Ayarlar',
        'logout' => 'Çıkış Yap',
        'login' => 'Giriş Yap',
        'register' => 'Kayıt Ol',
    ],

    // Actions
    'actions' => 'İşlemler',
    'save' => 'Kaydet',
    'cancel' => 'İptal',
    'edit' => 'Düzenle',
    'delete' => 'Sil',
    'create' => 'Oluştur',
    'search' => 'Ara',
    'filter' => 'Filtrele',
    'status' => 'Durum',
    'active' => 'Aktif',
    'passive' => 'Pasif',
    'update' => 'Güncelle',
    'back' => 'Geri',
    'select' => 'Seç',
    'clear' => 'Temizle',
    'show' => 'Göster',
    'hide' => 'Gizle',
    'add' => 'Ekle',
    'remove' => 'Kaldır',
    'confirm' => 'Onayla',
    'yes' => 'Evet',
    'no' => 'Hayır',
    'close' => 'Kapat',
    'submit' => 'Gönder',
    'reset' => 'Sıfırla',
    'download' => 'İndir',
    'upload' => 'Yükle',
    'print' => 'Yazdır',
    'export' => 'Dışa Aktar',
    'import' => 'İçe Aktar',

    // User
    'user' => [
        'name' => 'Ad Soyad',
        'email' => 'E-posta',
        'phone' => 'Telefon',
        'password' => 'Şifre',
        'password_confirmation' => 'Şifre Tekrar',
        'role' => 'Rol',
        'language' => 'Dil',
        'profile_photo' => 'Profil Fotoğrafı',
        'identity_number' => 'TC Kimlik No',
        'birth_date' => 'Doğum Tarihi',
        'gender' => 'Cinsiyet',
        'address' => 'Adres',
        'city' => 'Şehir',
        'country' => 'Ülke',
        'postal_code' => 'Posta Kodu',
        'medical_history' => 'Tıbbi Geçmiş',
        'allergies' => 'Alerjiler',
        'chronic_diseases' => 'Kronik Hastalıklar',
        'blood_type' => 'Kan Grubu',
        'emergency_contact_name' => 'Acil Durumda Aranacak Kişi',
        'emergency_contact_phone' => 'Acil Durumda Aranacak Telefon',
        'notes' => 'Notlar',
    ],

    // Gender
    'gender' => [
        'male' => 'Erkek',
        'female' => 'Kadın',
        'other' => 'Diğer',
    ],

    // Roles
    'roles' => [
        'super_admin' => 'Süper Yönetici',
        'admin' => 'Yönetici',
        'doctor' => 'Doktor',
        'nurse' => 'Hemşire',
        'patient' => 'Hasta',
        'receptionist' => 'Resepsiyonist',
        'representative' => 'Temsilci',
    ],
    
    // Status
    'enabled' => 'Etkin',
    'disabled' => 'Devre Dışı',
    'pending' => 'Beklemede',
    'completed' => 'Tamamlandı',
    'cancelled' => 'İptal Edildi',
    'failed' => 'Başarısız',
    'success' => 'Başarılı',
    'error' => 'Hata',
    'warning' => 'Uyarı',
    'info' => 'Bilgi',
    
    // Gender
    'gender.male' => 'Erkek',
    'gender.female' => 'Kadın',
    'gender.other' => 'Diğer',
    
    // Blood Types
    'blood_type' => 'Kan Grubu',
    'blood_type.a_positive' => 'A Rh+',
    'blood_type.a_negative' => 'A Rh-',
    'blood_type.b_positive' => 'B Rh+',
    'blood_type.b_negative' => 'B Rh-',
    'blood_type.ab_positive' => 'AB Rh+',
    'blood_type.ab_negative' => 'AB Rh-',
    'blood_type.o_positive' => '0 Rh+',
    'blood_type.o_negative' => '0 Rh-',
    
    // Messages
    'success_message' => 'İşlem başarıyla tamamlandı',
    'error_message' => 'Bir hata oluştu',
    'confirm_delete' => 'Bu kaydı silmek istediğinizden emin misiniz?',
    'no_records_found' => 'Kayıt bulunamadı',
    'loading' => 'Yükleniyor...',
    'processing' => 'İşleniyor...',
    'please_wait' => 'Lütfen bekleyin...',
    
    // Validation
    'required' => ':attribute alanı zorunludur',
    'email' => 'Geçerli bir e-posta adresi giriniz',
    'min' => ':attribute en az :min karakter olmalıdır',
    'max' => ':attribute en fazla :max karakter olmalıdır',
    'numeric' => ':attribute sayısal bir değer olmalıdır',
    'date' => ':attribute geçerli bir tarih olmalıdır',
    'unique' => ':attribute zaten kayıtlı',
    'confirmed' => ':attribute doğrulaması eşleşmiyor',
    'password' => 'Şifre en az 8 karakter olmalı ve en az bir büyük harf, bir küçük harf ve bir rakam içermelidir',
    
    // Pagination
    'previous' => 'Önceki',
    'next' => 'Sonraki',
    'showing' => 'Gösteriliyor',
    'to' => 'ile',
    'of' => 'toplam',
    'entries' => 'kayıt',
    
    // Date/Time
    'today' => 'Bugün',
    'yesterday' => 'Dün',
    'tomorrow' => 'Yarın',
    'this_week' => 'Bu Hafta',
    'this_month' => 'Bu Ay',
    'this_year' => 'Bu Yıl',
    'date_format' => 'd.m.Y',
    'time_format' => 'H:i',
    'datetime_format' => 'd.m.Y H:i',
    'emergency_contact_deleted' => 'Acil durum kişisi başarıyla silindi.',
    'emergency_contact_delete_error' => 'Acil durum kişisi silinirken bir hata oluştu.',
]; 