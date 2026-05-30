<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('routine_slots', function (Blueprint $table) {
            $table->date('date')->nullable()->after('day_of_week');
        });
    }

    public function down(): void
    {
        Schema::table('routine_slots', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
};
