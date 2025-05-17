<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Karakter setini ayarla
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Roles tablosu
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Rol Adı');
            $table->string('slug')->unique()->comment('Rol Kısa Adı (Benzersiz)');
            $table->text('description')->nullable()->comment('Rol Açıklaması');
            $table->boolean('is_active')->default(true)->comment('Aktif mi?');
            $table->timestamps();
            $table->softDeletes();
        });

        // Hospitals tablosu
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('clinic_name')->nullable()->comment('Klinik Adı');
            $table->string('phone', 20)->comment('Telefon Numarası');
            $table->string('email')->comment('E-posta Adresi');
            $table->string('tax_number')->nullable()->comment('Vergi Numarası');
            $table->text('address')->nullable()->comment('Adres');
            $table->string('city', 100)->nullable()->comment('Şehir');
            $table->string('country', 100)->nullable()->comment('Ülke');
            $table->string('website')->nullable()->comment('Web Sitesi');
            $table->text('description')->nullable()->comment('Açıklama');
            $table->text('notes')->nullable()->comment('Notlar');
            $table->string('logo')->nullable()->comment('Logo Dosya Yolu');
            $table->timestamp('trial_start_date')->nullable()->comment('Deneme Başlangıç Tarihi');
            $table->timestamp('trial_end_date')->nullable()->comment('Deneme Bitiş Tarihi');
            $table->timestamp('subscription_start_date')->nullable()->comment('Abonelik Başlangıç Tarihi');
            $table->timestamp('subscription_end_date')->nullable()->comment('Abonelik Bitiş Tarihi');
            $table->string('status')->default('trial')->comment('Durum (trial, active, expired)');
            $table->timestamps();
            $table->softDeletes();
        });

        // Users tablosu
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Ad Soyad');
            $table->string('email')->unique()->comment('E-posta');
            $table->string('password')->comment('Şifre (Hash)');
            $table->string('phone', 20)->nullable()->comment('Telefon');
            $table->foreignId('role_id')->constrained()->comment('Rol ID');
            $table->foreignId('hospital_id')->nullable()->constrained()->comment('Hastane ID');
            $table->boolean('is_active')->default(false)->comment('Aktif mi?');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Patients tablosu
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('identity_number', 11)->nullable()->unique()->comment('TC Kimlik No');
            $table->date('birth_date')->nullable()->comment('Doğum Tarihi');
            $table->string('gender', 10)->nullable()->comment('Cinsiyet');
            $table->text('address')->nullable()->comment('Adres');
            $table->string('city', 100)->nullable()->comment('Şehir');
            $table->string('country', 100)->nullable()->comment('Ülke');
            $table->string('postal_code', 20)->nullable()->comment('Posta Kodu');
            $table->text('medical_history')->nullable()->comment('Tıbbi Geçmiş');
            $table->json('allergies')->nullable()->comment('Alerjiler');
            $table->json('chronic_diseases')->nullable()->comment('Kronik Hastalıklar');
            $table->json('medications_used')->nullable()->comment('Kullanılan İlaçlar');
            $table->string('blood_type', 10)->nullable()->comment('Kan Grubu');
            $table->text('notes')->nullable()->comment('Notlar');
            $table->integer('height')->nullable()->comment('Boy (cm)');
            $table->integer('weight')->nullable()->comment('Kilo (kg)');
            $table->enum('smoking_status', ['never', 'former', 'current'])->nullable()->comment('Sigara Kullanımı');
            $table->enum('alcohol_consumption', ['never', 'occasional', 'regular', 'former'])->nullable()->comment('Alkol Kullanımı');
            $table->enum('exercise_status', ['none', 'occasional', 'regular'])->nullable()->comment('Egzersiz Durumu');
            $table->text('dietary_habits')->nullable()->comment('Beslenme Alışkanlıkları');
            $table->string('occupation', 100)->nullable()->comment('Meslek');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable()->comment('Medeni Durum');
            $table->boolean('is_verified')->default(false)->comment('Doğrulanmış mı?');
            $table->string('verification_code', 6)->nullable()->comment('Doğrulama Kodu');
            $table->timestamp('verification_code_expires_at')->nullable()->comment('Doğrulama Kodu Son Kullanma Tarihi');
            $table->string('emergency_contact_name')->nullable()->comment('Acil Durum Kişisi Adı');
            $table->string('emergency_contact_phone', 20)->nullable()->comment('Acil Durum Kişisi Telefonu');
            $table->string('emergency_contact_relation', 100)->nullable()->comment('Acil Durum Kişisi Yakınlık Derecesi');
            $table->timestamps();
            $table->softDeletes();
        });

        // Emergency_contacts tablosu
        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->string('name')->comment('Ad Soyad');
            $table->string('phone', 20)->comment('Telefon');
            $table->string('relation', 100)->nullable()->comment('Yakınlık Derecesi');
            $table->timestamps();
            $table->softDeletes();
        });

        // Karakter setini ayarla
        DB::statement('ALTER TABLE roles CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE hospitals CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE users CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE patients CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE emergency_contacts CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        Schema::dropIfExists('emergency_contacts');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('users');
        Schema::dropIfExists('hospitals');
        Schema::dropIfExists('roles');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}; 