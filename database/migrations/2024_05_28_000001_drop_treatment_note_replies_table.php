<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::dropIfExists('treatment_note_replies');
    }
    public function down() {
        // Geri alma işlemi: tabloyu tekrar oluşturmak isterseniz buraya ekleyin
    }
}; 