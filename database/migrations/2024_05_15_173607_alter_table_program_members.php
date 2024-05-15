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
        Schema::table('program_members', function (Blueprint $table){
            $table->date('date')->after('program_data_id');
            $table->string('status')->after('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_members', function (Blueprint $table){
            $table->dropColumn('date');
            $table->dropColumn('status');

        });
    }
};
