<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolUnit;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\Subject;
use App\Models\LearningObjective;
use App\Models\AcademicGrade;
use App\Models\QuranCriterion;
use App\Models\QuranGrade;
use App\Models\CharacterIndicator;
use App\Models\CharacterGrade;
use App\Models\ExtracurricularGrade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    protected function getActiveUnit(): SchoolUnit
    {
        $unitId = session('active_school_unit_id');
        if ($unitId) {
            $unit = SchoolUnit::find($unitId);
            if ($unit) return $unit;
        }
        $unit = SchoolUnit::where('code', 'sdit')->first() ?? SchoolUnit::first();
        session(['active_school_unit_id' => $unit->id]);
        return $unit;
    }

    // 1. Penilaian Akademik (Kurikulum Merdeka)
    public function academic(Request $request)
    {
        $activeUnit = $this->getActiveUnit();
        $classrooms = Classroom::where('school_unit_id', $activeUnit->id)->get();
        $subjects = Subject::where('school_unit_id', $activeUnit->id)->orderBy('order_number')->get();

        $selectedClassroomId = $request->input('classroom_id', $classrooms->first()->id ?? null);
        $selectedSubjectId = $request->input('subject_id', $subjects->first()->id ?? null);

        $selectedClassroom = Classroom::with('classroomStudents.student')->find($selectedClassroomId);
        $selectedSubject = Subject::with('learningObjectives')->find($selectedSubjectId);

        $studentsData = [];
        if ($selectedClassroom && $selectedSubject) {
            $learningObjectives = LearningObjective::where('subject_id', $selectedSubject->id)
                ->where('grade_level', $selectedClassroom->grade_level)
                ->get();

            foreach ($selectedClassroom->classroomStudents as $cs) {
                $grade = AcademicGrade::where('classroom_student_id', $cs->id)
                    ->where('subject_id', $selectedSubject->id)
                    ->first();

                $studentsData[] = [
                    'classroom_student' => $cs,
                    'grade' => $grade,
                    'formative_scores' => $grade ? ($grade->formative_scores ?? []) : [],
                ];
            }
        } else {
            $learningObjectives = collect();
        }

        return view('admin.grades.academic', compact(
            'activeUnit',
            'classrooms',
            'subjects',
            'selectedClassroom',
            'selectedSubject',
            'learningObjectives',
            'studentsData'
        ));
    }

    public function saveAcademic(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'grades' => 'required|array',
        ]);

        $subject = Subject::findOrFail($request->subject_id);

        foreach ($request->grades as $csId => $data) {
            $formative = $data['formative'] ?? [];
            $scoreMaterial = !empty($data['score_sumative_material']) ? (float)$data['score_sumative_material'] : null;
            $scoreFinal = !empty($data['score_sumative_final']) ? (float)$data['score_sumative_final'] : null;

            // Kalkulasi Final Grade jika tidak diinput manual
            if (isset($data['final_grade']) && $data['final_grade'] !== '') {
                $finalGrade = (float)$data['final_grade'];
            } else {
                $avgFormative = !empty($formative) ? (array_sum($formative) / count($formative)) : 0;
                $finalGrade = round(($avgFormative + ($scoreMaterial ?? 80) + ($scoreFinal ?? 80)) / 3, 1);
            }

            $academicGrade = AcademicGrade::updateOrCreate([
                'classroom_student_id' => $csId,
                'subject_id' => $subject->id,
            ], [
                'formative_scores' => $formative,
                'score_sumative_material' => $scoreMaterial,
                'score_sumative_final' => $scoreFinal,
                'final_grade' => $finalGrade,
                'highest_achievement' => $data['highest_achievement'] ?? null,
                'lowest_achievement' => $data['lowest_achievement'] ?? null,
                'teacher_notes' => $data['teacher_notes'] ?? null,
            ]);

            // Jika deskripsi belum diisi, generate otomatis
            if (empty($data['highest_achievement']) || empty($data['lowest_achievement'])) {
                $narr = $academicGrade->generateNarrative();
                $academicGrade->update([
                    'highest_achievement' => $data['highest_achievement'] ?: $narr['highest'],
                    'lowest_achievement' => $data['lowest_achievement'] ?: $narr['lowest'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'Nilai akademik kurikulum merdeka berhasil disimpan dan deskripsi otomatis diperbarui!');
    }

    // 2. Penilaian Al-Qur'an (Metode Wafa & Dinamis)
    public function quran(Request $request)
    {
        $activeUnit = $this->getActiveUnit();
        $classrooms = Classroom::where('school_unit_id', $activeUnit->id)->get();
        $selectedClassroomId = $request->input('classroom_id', $classrooms->first()->id ?? null);
        $selectedClassroom = Classroom::with('classroomStudents.student')->find($selectedClassroomId);

        $tahsinCriteria = QuranCriterion::where('school_unit_id', $activeUnit->id)
            ->where('category', 'tahsin')
            ->orderBy('order_number')
            ->get();

        $studentsData = [];
        if ($selectedClassroom) {
            foreach ($selectedClassroom->classroomStudents as $cs) {
                $grade = QuranGrade::where('classroom_student_id', $cs->id)->first();
                $studentsData[] = [
                    'classroom_student' => $cs,
                    'grade' => $grade,
                    'tahsin_scores' => $grade ? ($grade->tahsin_scores ?? []) : [],
                ];
            }
        }

        return view('admin.grades.quran', compact(
            'activeUnit',
            'classrooms',
            'selectedClassroom',
            'tahsinCriteria',
            'studentsData'
        ));
    }

    public function saveQuran(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'quran' => 'required|array',
        ]);

        foreach ($request->quran as $csId => $data) {
            $tahsinScores = $data['tahsin_scores'] ?? [];
            $avgTahsin = !empty($tahsinScores) ? (array_sum($tahsinScores) / count($tahsinScores)) : null;

            // Predikat Tahsin Wafa otomatis jika tidak diisi
            $tahsinScore = !empty($data['tahsin_final_score']) ? (float)$data['tahsin_final_score'] : ($avgTahsin ? round($avgTahsin, 1) : 85);
            $predicate = $data['tahsin_predicate'] ?? '';
            if (empty($predicate)) {
                if ($tahsinScore >= 90) $predicate = 'Mumtaz (Istimewa)';
                elseif ($tahsinScore >= 80) $predicate = 'Jayyid Jiddan (Sangat Baik)';
                elseif ($tahsinScore >= 70) $predicate = 'Jayyid (Baik)';
                else $predicate = 'Maqbul (Cukup)';
            }

            QuranGrade::updateOrCreate([
                'classroom_student_id' => $csId,
            ], [
                'tahsin_method' => $data['tahsin_method'] ?? 'Wafa',
                'tahsin_level' => $data['tahsin_level'] ?? null,
                'tahsin_scores' => $tahsinScores,
                'tahsin_final_score' => $tahsinScore,
                'tahsin_predicate' => $predicate,
                'tahsin_notes' => $data['tahsin_notes'] ?? null,
                'tahfidz_target' => $data['tahfidz_target'] ?? null,
                'tahfidz_achievement' => $data['tahfidz_achievement'] ?? null,
                'tahfidz_score' => !empty($data['tahfidz_score']) ? (float)$data['tahfidz_score'] : null,
                'tahfidz_predicate' => $data['tahfidz_predicate'] ?? 'Mutqin',
                'tasmi_exam_result' => $data['tasmi_exam_result'] ?? null,
                'tahfidz_notes' => $data['tahfidz_notes'] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Nilai Al-Qur\'an Metode Wafa (Tahsin & Tahfidz) berhasil disimpan.');
    }

    // 3. Penilaian Karakter & BPI (7 SKL JSIT)
    public function character(Request $request)
    {
        $activeUnit = $this->getActiveUnit();
        $classrooms = Classroom::where('school_unit_id', $activeUnit->id)->get();
        $selectedClassroomId = $request->input('classroom_id', $classrooms->first()->id ?? null);
        $selectedClassroom = Classroom::with('classroomStudents.student')->find($selectedClassroomId);

        $indicators = CharacterIndicator::where('school_unit_id', $activeUnit->id)
            ->orderBy('order_number')
            ->get();

        $studentsData = [];
        if ($selectedClassroom) {
            foreach ($selectedClassroom->classroomStudents as $cs) {
                $grade = CharacterGrade::where('classroom_student_id', $cs->id)->first();
                $studentsData[] = [
                    'classroom_student' => $cs,
                    'grade' => $grade,
                    'indicator_scores' => $grade ? ($grade->indicator_scores ?? []) : [],
                ];
            }
        }

        return view('admin.grades.character', compact(
            'activeUnit',
            'classrooms',
            'selectedClassroom',
            'indicators',
            'studentsData'
        ));
    }

    public function saveCharacter(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'character' => 'required|array',
        ]);

        foreach ($request->character as $csId => $data) {
            CharacterGrade::updateOrCreate([
                'classroom_student_id' => $csId,
            ], [
                'indicator_scores' => $data['indicators'] ?? [],
                'mutabaah_sholat_fardhu' => $data['mutabaah_sholat_fardhu'] ?? 'Selalu Berjamaah',
                'mutabaah_sholat_dhuha' => $data['mutabaah_sholat_dhuha'] ?? 'Rutin',
                'mutabaah_tilawah' => $data['mutabaah_tilawah'] ?? 'Rutin Tiap Hari',
                'mutabaah_infaq' => $data['mutabaah_infaq'] ?? 'Rutin Infaq Jumat',
                'bpi_mentor_notes' => $data['bpi_mentor_notes'] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Nilai Karakter 7 SKL JSIT & Rekap Mutabaah BPI berhasil disimpan.');
    }

    // 4. Catatan Wali Kelas, Absensi, Kesehatan & Ekstrakurikuler
    public function homeroom(Request $request)
    {
        $activeUnit = $this->getActiveUnit();
        $classrooms = Classroom::where('school_unit_id', $activeUnit->id)->get();
        $selectedClassroomId = $request->input('classroom_id', $classrooms->first()->id ?? null);
        $selectedClassroom = Classroom::with(['classroomStudents.student', 'classroomStudents.extracurricularGrades'])->find($selectedClassroomId);

        return view('admin.grades.homeroom', compact('activeUnit', 'classrooms', 'selectedClassroom'));
    }

    public function saveHomeroom(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'students' => 'required|array',
        ]);

        foreach ($request->students as $csId => $data) {
            $cs = ClassroomStudent::findOrFail($csId);
            $cs->update([
                'attendance_sakit' => (int)($data['attendance_sakit'] ?? 0),
                'attendance_izin' => (int)($data['attendance_izin'] ?? 0),
                'attendance_alpa' => (int)($data['attendance_alpa'] ?? 0),
                'physical_height' => !empty($data['physical_height']) ? (int)$data['physical_height'] : null,
                'physical_weight' => !empty($data['physical_weight']) ? (int)$data['physical_weight'] : null,
                'physical_hearing' => $data['physical_hearing'] ?? 'Normal',
                'physical_vision' => $data['physical_vision'] ?? 'Normal',
                'physical_dental' => $data['physical_dental'] ?? 'Bersih',
                'homeroom_notes' => $data['homeroom_notes'] ?? null,
                'status' => $data['status'] ?? 'aktif',
            ]);

            // Ekstrakurikuler
            if (!empty($data['ekstra_name'])) {
                ExtracurricularGrade::updateOrCreate([
                    'classroom_student_id' => $cs->id,
                    'activity_name' => $data['ekstra_name'],
                ], [
                    'predicate' => $data['ekstra_pred'] ?? 'Sangat Baik',
                    'description' => $data['ekstra_desc'] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Catatan Wali Kelas, Absensi, Kesehatan, dan Ekstrakurikuler berhasil disimpan.');
    }
}
