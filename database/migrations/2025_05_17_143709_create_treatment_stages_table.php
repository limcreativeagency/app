<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_stages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('treatment_id');
            $table->string('title'); // Örn: Kabuk Dökümü
            $table->integer('day_after_operation');
            $table->text('instructions')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->date('due_date')->nullable();
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
        Schema::dropIfExists('treatment_stages');
    }
}
