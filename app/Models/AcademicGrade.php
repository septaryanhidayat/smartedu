<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_student_id',
        'subject_id',
        'formative_scores',
        'score_sumative_material',
        'score_sumative_final',
        'final_grade',
        'highest_achievement',
        'lowest_achievement',
        'teacher_notes',
    ];

    protected function casts(): array
    {
        return [
            'formative_scores' => 'array',
            'score_sumative_material' => 'float',
            'score_sumative_final' => 'float',
            'final_grade' => 'float',
        ];
    }

    public function classroomStudent(): BelongsTo
    {
        return $this->belongsTo(ClassroomStudent::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Generate Auto Narrative berdasarkan skor TP (Tujuan Pembelajaran)
     */
    public function generateNarrative(): array
    {
        $scores = $this->formative_scores ?? [];
        if (empty($scores)) {
            // Fallback jika tidak ada skor per TP individual, gunakan final_grade
            $grade = $this->final_grade ?? 80;
            if ($grade >= 85) {
                return [
                    'highest' => "Menunjukkan pemahaman yang sangat baik dalam menguasai seluruh materi dan konsep {$this->subject->name}.",
                    'lowest' => "Perlu terus mempertahankan motivasi belajar dan memperluas wawasan literasi pada {$this->subject->name}."
                ];
            } else {
                return [
                    'highest' => "Menunjukkan penguasaan yang cukup baik pada capaian materi umum {$this->subject->name}.",
                    'lowest' => "Perlu bimbingan dan latihan lebih intensif dalam penguatan konsep dasar materi {$this->subject->name}."
                ];
            }
        }

        // Ambil objek TP dari database
        $tpIds = array_keys($scores);
        $learningObjectives = LearningObjective::whereIn('id', $tpIds)->get()->keyBy('id');

        arsort($scores); // Urutkan dari tertinggi ke terendah
        $highestTpId = array_key_first($scores);
        $lowestTpId = array_key_last($scores);

        $highestDesc = "Menunjukkan penguasaan yang sangat baik dalam ";
        if (isset($learningObjectives[$highestTpId])) {
            $highestDesc .= lcfirst($learningObjectives[$highestTpId]->description);
        } else {
            $highestDesc .= "capaian materi utama.";
        }

        $lowestDesc = "Perlu bimbingan lebih lanjut dalam ";
        if (isset($learningObjectives[$lowestTpId]) && $scores[$lowestTpId] < 80) {
            $lowestDesc .= lcfirst($learningObjectives[$lowestTpId]->description);
        } else {
            $lowestDesc = "Menunjukkan konsistensi penguasaan yang merata pada seluruh tujuan pembelajaran yang telah dipelajari.";
        }

        return [
            'highest' => $highestDesc,
            'lowest' => $lowestDesc,
        ];
    }
}
