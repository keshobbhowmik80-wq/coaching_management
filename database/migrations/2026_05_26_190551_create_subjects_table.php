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
        Schema::create('subjects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->unsignedSmallInteger('full_marks')->default(100);
            $table->unsignedSmallInteger('pass_marks')->default(33);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
