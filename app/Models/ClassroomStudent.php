<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ClassroomStudent extends Model
{
    use HasFactory;

    protected $table = 'classroom_students';

    protected $fillable = [
        'classroom_id',
        'student_id',
        'academic_year_id',
        'attendance_sakit',
        'attendance_izin',
        'attendance_alpa',
        'homeroom_notes',
        'physical_height',
        'physical_weight',
        'physical_hearing',
        'physical_vision',
        'physical_dental',
        'status',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function academicGrades(): HasMany
    {
        return $this->hasMany(AcademicGrade::class);
    }

    public function extracurricularGrades(): HasMany
    {
        return $this->hasMany(ExtracurricularGrade::class);
    }

    public function quranGrade(): HasOne
    {
        return $this->hasOne(QuranGrade::class);
    }

    public function characterGrade(): HasOne
    {
        return $this->hasOne(CharacterGrade::class);
    }

    public function calculateAverageGrade(): float
    {
        $avg = $this->academicGrades()->whereNotNull('final_grade')->avg('final_grade');
        return $avg ? round($avg, 1) : 0;
    }
}
