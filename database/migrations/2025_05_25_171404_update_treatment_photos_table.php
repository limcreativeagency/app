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
            if (Schema::hasColumn('treatment_photos', 'photo_type')) {
                $table->dropColumn('photo_type');
            }
            if (Schema::hasColumn('treatment_photos', 'photo_category')) {
                $table->dropColumn('photo_category');
            }
            if (Schema::hasColumn('treatment_photos', 'photo_angle')) {
                $table->dropColumn('photo_angle');
            }
            if (Schema::hasColumn('treatment_photos', 'photo_notes')) {
                $table->dropColumn('photo_notes');
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
            $table->string('photo_type')->nullable();
            $table->string('photo_category')->nullable();
            $table->string('photo_angle')->nullable();
            $table->text('photo_notes')->nullable();
        });
    }
};
