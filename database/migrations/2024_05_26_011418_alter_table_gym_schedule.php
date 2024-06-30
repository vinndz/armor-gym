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
        Schema::table('gym_schedules', function (Blueprint $table) {
            $table->dateTime('date')->change();
            $table->string('status')->after('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gym_schedules', function (Blueprint $table) {
            $table->date('date')->change();
            $table->dropColumn('status');
        });
    }
};
