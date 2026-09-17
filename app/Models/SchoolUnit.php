<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolUnit extends Model
{
    use HasFactory;

    protected $table = 'schools';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'print_settings' => 'array',
            'report_date' => 'date',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function academicYears(): HasMany
    {
        return $this->hasMany(AcademicYear::class);
    }

    public function activeAcademicYear()
    {
        return $this->academicYears()->where('is_active', true)->first();
    }

    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class)->orderBy('order_number');
    }

    public function quranCriteria(): HasMany
    {
        return $this->hasMany(QuranCriterion::class)->orderBy('order_number');
    }

    public function characterIndicators(): HasMany
    {
        return $this->hasMany(CharacterIndicator::class)->orderBy('order_number');
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo_path && file_exists(public_path($this->logo_path))) {
            return asset($this->logo_path);
        }
        return asset('images/logo_default.png');
    }

    public function getLetterheadUrlAttribute(): ?string
    {
        if ($this->letterhead_path && file_exists(public_path($this->letterhead_path))) {
            return asset($this->letterhead_path);
        }
        return null;
    }

    public function getStampUrlAttribute(): ?string
    {
        if ($this->stamp_path && file_exists(public_path($this->stamp_path))) {
            return asset($this->stamp_path);
        }
        return null;
    }

    public function getPrincipalSignatureUrlAttribute(): ?string
    {
        if ($this->principal_signature_path && file_exists(public_path($this->principal_signature_path))) {
            return asset($this->principal_signature_path);
        }
        return null;
    }

    public function getQuranCoordinatorSignatureUrlAttribute(): ?string
    {
        if ($this->quran_coordinator_signature_path && file_exists(public_path($this->quran_coordinator_signature_path))) {
            return asset($this->quran_coordinator_signature_path);
        }
        return null;
    }
}
