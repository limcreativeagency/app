<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->string('treatment_type'); // Örn: FUE, DHI
            $table->string('treatment_area')->nullable(); // Ön saç çizgisi vb.
            $table->integer('graft_count')->nullable();
            $table->date('operation_date');
            $table->string('team_responsible')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('treatments');
    }
}
