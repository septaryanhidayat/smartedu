<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtracurricularGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_student_id',
        'activity_name',
        'predicate',
        'description',
    ];

    public function classroomStudent(): BelongsTo
    {
        return $this->belongsTo(ClassroomStudent::class);
    }
}
