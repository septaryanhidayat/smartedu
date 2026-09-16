<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolUnit;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\Subject;
use App\Models\QuranCriterion;
use App\Models\CharacterIndicator;
use Illuminate\Http\Request;

class ReportPrintController extends Controller
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

    public function index(Request $request)
    {
        $activeUnit = $this->getActiveUnit();
        $classrooms = Classroom::where('school_unit_id', $activeUnit->id)->get();
        $selectedClassroomId = $request->input('classroom_id', $classrooms->first()->id ?? null);
        $selectedClassroom = Classroom::with([
            'classroomStudents.student',
            'classroomStudents.academicGrades',
            'classroomStudents.quranGrade',
            'classroomStudents.characterGrade',
            'homeroomTeacher',
            'academicYear'
        ])->find($selectedClassroomId);

        return view('admin.reports.index', compact('activeUnit', 'classrooms', 'selectedClassroom'));
    }

    // 1. Cetak Terpisah: Rapor Akademik (Kurikulum Merdeka)
    public function printAcademic($id)
    {
        $cs = ClassroomStudent::with([
            'classroom.schoolUnit',
            'classroom.homeroomTeacher',
            'academicYear',
            'student',
            'academicGrades.subject',
            'extracurricularGrades'
        ])->findOrFail($id);

        $unit = $cs->classroom->schoolUnit;

        // Kelompokkan mapel: nasional & muatan lokal / diniyah
        $academicGrades = $cs->academicGrades->sortBy('subject.order_number');

        return view('admin.reports.print_academic', compact('cs', 'unit', 'academicGrades'));
    }

    // 2. Cetak Terpisah: Rapor Al-Qur'an (Metode Wafa)
    public function printQuran($id)
    {
        $cs = ClassroomStudent::with([
            'classroom.schoolUnit',
            'classroom.homeroomTeacher',
            'academicYear',
            'student',
            'quranGrade.examinerTeacher'
        ])->findOrFail($id);

        $unit = $cs->classroom->schoolUnit;
        $quranGrade = $cs->quranGrade;
        $criteria = QuranCriterion::where('school_unit_id', $unit->id)
            ->where('category', 'tahsin')
            ->orderBy('order_number')
            ->get();

        return view('admin.reports.print_quran', compact('cs', 'unit', 'quranGrade', 'criteria'));
    }

    // 3. Cetak Terpisah: Rapor Karakter & BPI (7 SKL JSIT)
    public function printCharacter($id)
    {
        $cs = ClassroomStudent::with([
            'classroom.schoolUnit',
            'classroom.homeroomTeacher',
            'academicYear',
            'student',
            'characterGrade'
        ])->findOrFail($id);

        $unit = $cs->classroom->schoolUnit;
        $characterGrade = $cs->characterGrade;
        $indicators = CharacterIndicator::where('school_unit_id', $unit->id)
            ->orderBy('order_number')
            ->get();

        return view('admin.reports.print_character', compact('cs', 'unit', 'characterGrade', 'indicators'));
    }

    // 4. Cetak Gabungan: All-in-One E-Rapor Terpadu
    public function printBundle($id)
    {
        $cs = ClassroomStudent::with([
            'classroom.schoolUnit',
            'classroom.homeroomTeacher',
            'academicYear',
            'student',
            'academicGrades.subject',
            'extracurricularGrades',
            'quranGrade.examinerTeacher',
            'characterGrade'
        ])->findOrFail($id);

        $unit = $cs->classroom->schoolUnit;
        $academicGrades = $cs->academicGrades->sortBy('subject.order_number');
        $quranGrade = $cs->quranGrade;
        $quranCriteria = QuranCriterion::where('school_unit_id', $unit->id)
            ->where('category', 'tahsin')
            ->orderBy('order_number')
            ->get();
        $characterGrade = $cs->characterGrade;
        $characterIndicators = CharacterIndicator::where('school_unit_id', $unit->id)
            ->orderBy('order_number')
            ->get();

        return view('admin.reports.print_bundle', compact(
            'cs',
            'unit',
            'academicGrades',
            'quranGrade',
            'quranCriteria',
            'characterGrade',
            'characterIndicators'
        ));
    }

    // 5. Cetak Leger Nilai Kelas
    public function printLeger($classroomId)
    {
        $classroom = Classroom::with([
            'schoolUnit',
            'academicYear',
            'homeroomTeacher',
            'classroomStudents.student',
            'classroomStudents.academicGrades.subject',
            'classroomStudents.quranGrade'
        ])->findOrFail($classroomId);

        $unit = $classroom->schoolUnit;
        $subjects = Subject::where('school_unit_id', $unit->id)->orderBy('order_number')->get();

        return view('admin.reports.print_leger', compact('classroom', 'unit', 'subjects'));
    }
}
