<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->string('carnet')->unique()->nullable()->after('last_name');
        });

        // Populate carnet with unique values based on graduate ID
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            DB::statement('UPDATE graduates SET carnet = "G" || id WHERE carnet IS NULL');
        } else {
            DB::statement('UPDATE graduates SET carnet = CONCAT("G", id) WHERE carnet IS NULL');
        }

        // Now make it non-nullable
        Schema::table('graduates', function (Blueprint $table) {
            $table->string('carnet')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->dropColumn('carnet');
        });
    }
};
