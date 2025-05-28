<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('treatment_notes', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
    }
    public function down() {
        Schema::table('treatment_notes', function (Blueprint $table) {
            $table->string('user_type')->nullable();
        });
    }
}; 