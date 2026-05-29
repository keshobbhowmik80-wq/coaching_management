<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check and drop foreign keys if they exist
        Schema::table('routines', function (Blueprint $table) {
            $foreignKeys = collect(Schema::getForeignKeys('routines'))->pluck('name')->toArray();
            if (in_array('routines_subject_id_foreign', $foreignKeys)) {
                $table->dropForeign(['subject_id']);
            }
            if (in_array('routines_teacher_id_foreign', $foreignKeys)) {
                $table->dropForeign(['teacher_id']);
            }
        });

        // Add new columns
        Schema::table('routines', function (Blueprint $table) {
            if (!Schema::hasColumn('routines', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('routines', 'type')) {
                $table->string('type')->default('class')->after('section_id')->index();
            }
            if (!Schema::hasColumn('routines', 'exam_id')) {
                $table->foreignId('exam_id')->nullable()->after('class_id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('routines', 'starts_on')) {
                $table->date('starts_on')->nullable()->after('exam_id');
            }
            if (!Schema::hasColumn('routines', 'ends_on')) {
                $table->date('ends_on')->nullable()->after('starts_on');
            }
        });

        // Create routine_slots table if not exists
        if (!Schema::hasTable('routine_slots')) {
            Schema::create('routine_slots', function (Blueprint $table) {
                $table->id();
                $table->foreignId('routine_id')->constrained('routines')->cascadeOnDelete();
                $table->string('day_of_week');
                $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
                $table->time('starts_at');
                $table->time('ends_at');
                $table->string('room')->nullable();
                $table->timestamps();
            });
        }

        // Drop old columns if they exist
        Schema::table('routines', function (Blueprint $table) {
            $columns = Schema::getColumns('routines');
            $columnNames = collect($columns)->pluck('name')->toArray();

            foreach (['subject_id', 'teacher_id', 'day_of_week', 'starts_at', 'ends_at', 'room'] as $col) {
                if (in_array($col, $columnNames)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void
    {
        // No rollback needed
    }
};
