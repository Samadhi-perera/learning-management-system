<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Year 1 Semester 1
            $table->string('academic_year'); // e.g. 2026/2027
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->nullOnDelete();
            $table->string('code')->unique(); // e.g. CS101, SE204
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('credits')->default(3);
            $table->string('thumbnail')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('enrolled_at')->useCurrent();
            $table->string('status')->default('enrolled'); // enrolled, completed, dropped
            $table->string('final_grade', 5)->nullable(); // e.g. A+, B, C
            $table->timestamps();

            $table->unique(['course_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('semesters');
    }
};
