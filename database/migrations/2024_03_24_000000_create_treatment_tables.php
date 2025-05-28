<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Treatment Stages tablosu
        Schema::create('treatment_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_id')->constrained()->onDelete('cascade');
            $table->string('name')->comment('Aşama Adı');
            $table->text('description')->nullable()->comment('Aşama Açıklaması');
            $table->integer('order')->default(0)->comment('Sıralama');
            $table->boolean('is_custom')->default(false)->comment('Özel Aşama mı?');
            $table->boolean('is_active')->default(true)->comment('Aktif mi?');
            $table->timestamps();
            $table->softDeletes();
        });

        // Treatments tablosu
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('title')->comment('Tedavi Başlığı');
            $table->text('description')->nullable()->comment('Tedavi Açıklaması');
            $table->date('start_date')->comment('Başlangıç Tarihi');
            $table->date('end_date')->nullable()->comment('Bitiş Tarihi');
            $table->string('status')->default('active')->comment('Durum (active, completed, cancelled)');
            $table->text('notes')->nullable();
            $table->string('treatment_type')->nullable();
            $table->date('operation_date')->nullable();
            $table->json('treatment_area')->nullable();
            $table->string('graft_count')->nullable();
            $table->json('details')->nullable()->comment('Ek Detaylar');
            $table->timestamps();
            $table->softDeletes();
        });

        // Treatment Photos tablosu
        Schema::create('treatment_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_id')->constrained();
            $table->string('image_path');
            $table->date('photo_date')->nullable();
            $table->string('photo_stage')->nullable();
            $table->string('stage_type')->nullable()->comment('Aşama Tipi');
            $table->integer('day_after_operation')->nullable()->comment('Operasyondan Sonraki Gün');
            $table->text('description')->nullable()->comment('Açıklama');
            $table->timestamps();
            $table->softDeletes();
        });

        // Karakter setini ayarla
        DB::statement('ALTER TABLE treatment_stages CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE treatments CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE treatment_photos CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    public function down()
    {
        Schema::dropIfExists('treatment_photos');
        Schema::dropIfExists('treatments');
        Schema::dropIfExists('treatment_stages');
    }
}; 