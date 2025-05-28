<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('treatment_notes', function (Blueprint $table) {
            $table->timestamp('read_at')->nullable()->after('is_visible_to_patient');
            $table->unsignedBigInteger('read_by')->nullable()->after('read_at');
        });
    }
    public function down() {
        Schema::table('treatment_notes', function (Blueprint $table) {
            $table->dropColumn(['read_at', 'read_by']);
        });
    }
}; 