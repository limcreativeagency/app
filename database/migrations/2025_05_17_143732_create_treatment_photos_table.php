<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('treatment_id');
            $table->string('stage_title')->nullable();
            $table->string('image_path');
            $table->timestamps();
        
            $table->foreign('treatment_id')->references('id')->on('treatments')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('treatment_photos');
    }
}
