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
        // 1. School Units (TKIT, SDIT, SMPIT, SMAIT)
        Schema::create('school_units', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. SDIT SmartEdu
            $table->string('code')->unique(); // sdit, smpit, tkit, smait
            $table->enum('level', ['tk', 'sd', 'smp', 'sma'])->default('sd');
            $table->string('npsn')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            
            // Legalitas & Cetak Rapor
            $table->string('logo_path')->nullable();
            $table->string('letterhead_path')->nullable(); // Gambar kop surat
            $table->string('stamp_path')->nullable(); // Gambar stempel sekolah
            
            // Pejabat Penandatangan
            $table->string('principal_name')->nullable();
            $table->string('principal_nip')->nullable();
            $table->string('principal_signature_path')->nullable();
            
            $table->string('quran_coordinator_name')->nullable();
            $table->string('quran_coordinator_nip')->nullable();
            $table->string('quran_coordinator_signature_path')->nullable();
            
            // Titimangsa Rapor
            $table->string('report_city')->default('Palembang');
            $table->date('report_date')->nullable();
            
            // Pengaturan cetak custom (json)
            $table->json('print_settings')->nullable();
            
            $table->timestamps();
        });

        // 2. Modifikasi tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('school_unit_id')->nullable()->after('id')->constrained('school_units')->nullOnDelete();
            $table->string('role')->default('yayasan_admin')->after('email'); // yayasan_admin, unit_admin, teacher, homeroom
            $table->string('phone')->nullable()->after('password');
            $table->string('nip')->nullable()->after('phone');
            $table->string('signature_path')->nullable()->after('nip');
        });

        // 3. Academic Years
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_unit_id')->constrained('school_units')->cascadeOnDelete();
            $table->string('name'); // e.g. 2025/2026
            $table->enum('semester', ['ganjil', 'genap'])->default('ganjil');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Classrooms (Rombongan Belajar)
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_unit_id')->constrained('school_units')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->string('name'); // e.g. "1 A - Abu Bakar Ash-Shiddiq"
            $table->unsignedTinyInteger('grade_level'); // 1 s/d 12
            $table->string('phase', 5)->default('A'); // Fase A, B, C, D, E, F
            $table->foreignId('homeroom_teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 5. Students
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_unit_id')->constrained('school_units')->cascadeOnDelete();
            $table->string('nis')->nullable();
            $table->string('nisn')->nullable();
            $table->string('full_name');
            $table->string('nickname')->nullable();
            $table->enum('gender', ['L', 'P'])->default('L');
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('parent_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('photo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 6. Classroom Students (Pivot Rombel & Siswa)
        Schema::create('classroom_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            
            // Absensi Semester
            $table->unsignedSmallInteger('attendance_sakit')->default(0);
            $table->unsignedSmallInteger('attendance_izin')->default(0);
            $table->unsignedSmallInteger('attendance_alpa')->default(0);
            
            // Catatan Wali Kelas
            $table->text('homeroom_notes')->nullable();
            
            // Rekap Perkembangan Fisik / Kesehatan
            $table->unsignedSmallInteger('physical_height')->nullable(); // cm
            $table->unsignedSmallInteger('physical_weight')->nullable(); // kg
            $table->string('physical_hearing')->nullable()->default('Baik');
            $table->string('physical_vision')->nullable()->default('Baik');
            $table->string('physical_dental')->nullable()->default('Bersih');
            
            // Status Kenaikan / Kelulusan
            $table->string('status')->default('aktif'); // aktif, naik_kelas, tinggal_kelas, lulus
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_students');
        Schema::dropIfExists('students');
        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('academic_years');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['school_unit_id']);
            $table->dropColumn(['school_unit_id', 'role', 'phone', 'nip', 'signature_path']);
        });
        
        Schema::dropIfExists('school_units');
    }
};
