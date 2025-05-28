<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Medication Plans tablosu
        Schema::create('medication_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('treatment_id')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->string('name')->comment('İlaç Planı Adı');
            $table->string('type')->nullable();
            $table->string('dose')->nullable();
            $table->json('times')->nullable();
            $table->text('description')->nullable()->comment('Açıklama');
            $table->date('start_date')->comment('Başlangıç Tarihi');
            $table->date('end_date')->nullable()->comment('Bitiş Tarihi');
            $table->text('notes')->nullable();
            $table->string('status')->default('active')->comment('Durum (active, completed, cancelled)');
            $table->timestamps();
            $table->softDeletes();
        });

        // Medication Usages tablosu
        Schema::create('medication_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medication_plan_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('time');
            $table->string('medication_name')->comment('İlaç Adı');
            $table->text('dosage')->nullable()->comment('Dozaj');
            $table->text('instructions')->nullable()->comment('Kullanım Talimatları');
            $table->boolean('taken')->default(false);
            $table->timestamp('taken_at')->nullable();
            $table->text('note')->nullable();
            $table->boolean('expired')->default(false)->comment('Süresi Dolmuş mu?');
            $table->timestamps();
            $table->softDeletes();
        });

        // Karakter setini ayarla
        DB::statement('ALTER TABLE medication_plans CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE medication_usages CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    public function down()
    {
        Schema::dropIfExists('medication_usages');
        Schema::dropIfExists('medication_plans');
    }
}; 