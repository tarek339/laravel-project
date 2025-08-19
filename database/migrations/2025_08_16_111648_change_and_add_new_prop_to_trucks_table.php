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
        Schema::table('trucks', function (Blueprint $table) {
            $table->dropColumn('assigned_to');
            $table->string('assigned_to_trailer')->nullable();
            $table->string('assigned_to_driver')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->dropColumn(['assigned_to_trailer', 'assigned_to_driver']);
            $table->string('assigned_to')->nullable(); // Recreate the deleted column for rollback
        });
    }
};
