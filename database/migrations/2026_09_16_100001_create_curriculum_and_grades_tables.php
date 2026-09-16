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
        // 1. Subjects (Mata Pelajaran: Nasional, Muatan Lokal, Diniyah SIT)
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_unit_id')->constrained('school_units')->cascadeOnDelete();
            $table->string('code', 20); // MTK, BIND, PAI, ARAB, FIQH, dll
            $table->string('name'); // Matematika, Bahasa Indonesia, Bahasa Arab Terpadu, dll
            $table->enum('category', ['nasional', 'muatan_lokal', 'diniyah_sit'])->default('nasional');
            $table->unsignedSmallInteger('order_number')->default(0);
            $table->timestamps();
        });

        // 2. Learning Objectives / Tujuan Pembelajaran (TP)
        Schema::create('learning_objectives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->unsignedTinyInteger('grade_level'); // 1 s/d 12
            $table->enum('semester', ['ganjil', 'genap'])->default('ganjil');
            $table->string('code', 20); // TP 1.1, TP 1.2
            $table->text('description'); // e.g. "Mampu memahami konsep bilangan bulat..."
            $table->timestamps();
        });

        // 3. Academic Grades (Nilai Rapor Akademik Kurikulum Merdeka)
        Schema::create('academic_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_student_id')->constrained('classroom_students')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            
            // Skor Formatif (per TP - json array {tp_id: score})
            $table->json('formative_scores')->nullable();
            
            // Nilai Sumatif Lingkup Materi & Sumatif Akhir Semester
            $table->decimal('score_sumative_material', 5, 2)->nullable(); // ASLM
            $table->decimal('score_sumative_final', 5, 2)->nullable(); // SAS / SAT
            $table->decimal('final_grade', 5, 2)->nullable(); // Nilai Rapor Akhir (0 - 100)
            
            // Capaian Tertinggi & Terendah (Auto-generated narrative dengan opsi edit guru)
            $table->text('highest_achievement')->nullable();
            $table->text('lowest_achievement')->nullable();
            $table->text('teacher_notes')->nullable();
            
            $table->timestamps();
        });

        // 4. Extracurricular Grades
        Schema::create('extracurricular_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_student_id')->constrained('classroom_students')->cascadeOnDelete();
            $table->string('activity_name'); // Pramuka SIT, Panahan, Karate, Seni Kaligrafi, dll
            $table->string('predicate', 20)->default('Sangat Baik'); // Sangat Baik, Baik, Cukup
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extracurricular_grades');
        Schema::dropIfExists('academic_grades');
        Schema::dropIfExists('learning_objectives');
        Schema::dropIfExists('subjects');
    }
};
