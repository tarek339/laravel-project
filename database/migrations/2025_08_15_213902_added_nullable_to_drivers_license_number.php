<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('license_number')->unique()->nullable()->change();
            $table->string('driver_card_number')->unique()->nullable()->change();
            $table->string('driver_qualification_number')->unique()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('license_number')->unique()->change();
            $table->string('driver_card_number')->nullable()->change();
            $table->string('driver_qualification_number')->nullable()->change();
        });
    }
};
