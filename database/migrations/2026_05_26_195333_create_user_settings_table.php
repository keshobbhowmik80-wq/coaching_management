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
        Schema::create('user_settings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('theme', ['light', 'dark'])->default('light');
            $table->timestamps();
        });

        DB::table('users')
            ->select('id')
            ->orderBy('id')
            ->chunk(100, function ($users): void {
                $now = now();

                DB::table('user_settings')->insert(
                    $users->map(fn ($user): array => [
                        'user_id' => $user->id,
                        'theme' => 'light',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])->all()
                );
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
