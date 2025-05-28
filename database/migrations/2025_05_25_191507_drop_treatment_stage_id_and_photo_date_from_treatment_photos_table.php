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
        Schema::table('treatment_photos', function (Blueprint $table) {
            if (Schema::hasColumn('treatment_photos', 'treatment_stage_id')) {
                $table->dropForeign(['treatment_stage_id']);
                $table->dropColumn('treatment_stage_id');
            }
            if (Schema::hasColumn('treatment_photos', 'photo_date')) {
                $table->dropColumn('photo_date');
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
        Schema::table('treatment_photos', function (Blueprint $table) {
            $table->foreignId('treatment_stage_id')->nullable()->constrained();
            $table->date('photo_date')->nullable();
        });
    }
};
