<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medication_usages', function (Blueprint $table) {
            if (Schema::hasColumn('medication_usages', 'instructions')) {
                $table->dropColumn('instructions');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medication_usages', function (Blueprint $table) {
            $table->text('instructions')->nullable()->comment('Kullanım Talimatları')->after('dosage');
        });
    }
};
