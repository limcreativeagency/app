<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// IMPORTANT: This migration requires the 'doctrine/dbal' package.
// Please run 'composer require doctrine/dbal' if you haven't already.

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            // Add new column for medications as JSON
            $table->json('medications_used')->nullable()->after('blood_type');

            // Change existing columns to JSON type
            // Ensure these columns exist from a previous migration
            if (Schema::hasColumn('patients', 'allergies')) {
                $table->json('allergies')->nullable()->change();
            }
            if (Schema::hasColumn('patients', 'chronic_diseases')) {
                $table->json('chronic_diseases')->nullable()->change();
            }

            // Drop old emergency contact columns
            $table->dropColumn(['emergency_contact_name', 'emergency_contact_phone']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            // Revert JSON columns to TEXT (or their original type)
            // Note: Reverting JSON to TEXT might lose formatting if data was complex JSON.
            // Adjust if original types were different.
            if (Schema::hasColumn('patients', 'allergies')) {
                $table->text('allergies')->nullable()->change();
            }
            if (Schema::hasColumn('patients', 'chronic_diseases')) {
                $table->text('chronic_diseases')->nullable()->change();
            }

            $table->dropColumn('medications_used');

            // Re-add emergency contact columns
            $table->string('emergency_contact_name')->nullable()->after('blood_type'); // Adjust position as needed
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
        });
    }
}; 