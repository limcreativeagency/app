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
            if (Schema::hasColumn('treatment_photos', 'stage_title')) {
                $table->dropColumn('stage_title');
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
            $table->string('stage_title')->nullable();
        });
    }
};
