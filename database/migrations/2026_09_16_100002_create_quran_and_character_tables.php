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
        // 1. Quran Criteria (Kriteria Penilaian Dinamis - Metode Wafa)
        Schema::create('quran_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_unit_id')->constrained('school_units')->cascadeOnDelete();
            $table->enum('category', ['tahsin', 'tahfidz'])->default('tahsin');
            $table->string('code', 30); // tilawah, makhraj, tajwid, nada_wafa, adab
            $table->string('name'); // Kelancaran Bacaan Wafa, Makharijul Huruf, Tajwid & Mad, Irama Wafa (Hijaz), Adab
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('order_number')->default(0);
            $table->timestamps();
        });

        // 2. Quran Grades (Rapor Al-Qur'an: Tahsin Wafa & Tahfidz)
        Schema::create('quran_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_student_id')->constrained('classroom_students')->cascadeOnDelete();
            
            // Tahsin Wafa Data
            $table->string('tahsin_method')->default('Wafa'); // Wafa
            $table->string('tahsin_level')->nullable(); // e.g. "Buku Wafa 3 Hal 28" atau "Al-Qur'an Juz 1"
            $table->json('tahsin_scores')->nullable(); // Skor detail per criterion id: {1: 85, 2: 90, ...}
            $table->decimal('tahsin_final_score', 5, 2)->nullable();
            $table->string('tahsin_predicate', 30)->nullable(); // Mumtaz, Jayyid Jiddan, Jayyid, Maqbul
            $table->text('tahsin_notes')->nullable();
            
            // Tahfidz Data
            $table->string('tahfidz_target')->nullable(); // e.g. "Juz 30 (An-Naba s/d An-Nas)"
            $table->string('tahfidz_achievement')->nullable(); // e.g. "Tuntas Juz 30 (Al-A'la s/d An-Nas)"
            $table->decimal('tahfidz_score', 5, 2)->nullable();
            $table->string('tahfidz_predicate', 30)->nullable(); // Mutqin, Cukup Mutqin
            $table->string('tasmi_exam_result')->nullable(); // e.g. "Lulus Tasmi' 1 Juz Sekali Duduk Predikat Mumtaz"
            $table->text('tahfidz_notes')->nullable();
            
            // Penguji / Ustadz
            $table->foreignId('examiner_teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 3. Character Indicators (7 Standar Mutu Karakter JSIT)
        Schema::create('character_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_unit_id')->constrained('school_units')->cascadeOnDelete();
            $table->string('standard_code', 20); // SKL_1, SKL_2, ... SKL_7
            $table->string('standard_name'); // e.g. "1. Salimul Aqidah (Akidah yang Bersih)"
            $table->text('indicator_name'); // e.g. "Meyakini Allah Maha Melihat, menjauhi tahayul/syirik, istiqomah berdoa"
            $table->unsignedSmallInteger('order_number')->default(0);
            $table->timestamps();
        });

        // 4. Character Grades (Rapor BPI & Karakter JSIT)
        Schema::create('character_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_student_id')->constrained('classroom_students')->cascadeOnDelete();
            
            // Skor Capaian 7 SKL ({indicator_id: 'SB' | 'B' | 'MB' | 'PB'})
            $table->json('indicator_scores')->nullable();
            
            // Rekap Mutabaah Yaumiyah
            $table->string('mutabaah_sholat_fardhu')->nullable()->default('Selalu Berjamaah');
            $table->string('mutabaah_sholat_dhuha')->nullable()->default('Rutin Setiap Hari');
            $table->string('mutabaah_tilawah')->nullable()->default('Rutin 1/2 Juz per Hari');
            $table->string('mutabaah_infaq')->nullable()->default('Rutin Infaq Jumat');
            
            // Catatan Pembina BPI / Mentoring
            $table->text('bpi_mentor_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_grades');
        Schema::dropIfExists('character_indicators');
        Schema::dropIfExists('quran_grades');
        Schema::dropIfExists('quran_criteria');
    }
};
