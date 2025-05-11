<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('role_id')->constrained('roles');
            $table->string('phone')->nullable();
            $table->string('language')->default('tr');
            $table->boolean('is_active')->default(true);
            
            // Hasta bilgileri
            $table->string('identity_number')->nullable()->unique(); // TC Kimlik No
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            
            // Sağlık bilgileri
            $table->text('medical_history')->nullable(); // Tıbbi geçmiş
            $table->text('allergies')->nullable(); // Alerjiler
            $table->text('chronic_diseases')->nullable(); // Kronik hastalıklar
            $table->string('blood_type')->nullable(); // Kan grubu
            $table->string('emergency_contact_name')->nullable(); // Acil durumda aranacak kişi
            $table->string('emergency_contact_phone')->nullable(); // Acil durumda aranacak telefon
            
            // Profil bilgileri
            $table->string('profile_photo')->nullable();
            $table->text('notes')->nullable(); // Genel notlar
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
