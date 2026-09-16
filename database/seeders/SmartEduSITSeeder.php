<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolUnit;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\ClassroomStudent;
use App\Models\Subject;
use App\Models\LearningObjective;
use App\Models\AcademicGrade;
use App\Models\ExtracurricularGrade;
use App\Models\QuranCriterion;
use App\Models\QuranGrade;
use App\Models\CharacterIndicator;
use App\Models\CharacterGrade;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SmartEduSITSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat / Perbarui User Admin Yayasan
        $admin = User::firstOrCreate(
            ['email' => 'admin@smartedu.test'],
            [
                'name' => 'Administrator Yayasan SmartEdu',
                'password' => Hash::make('p4l3mb4ng'),
                'role' => 'yayasan_admin',
            ]
        );

        // 2. Buat Unit Sekolah (TKIT, SDIT, SMPIT, SMAIT)
        $sdit = SchoolUnit::updateOrCreate(
            ['code' => 'sdit'],
            [
                'name' => 'SDIT SmartEdu Palembang',
                'level' => 'sd',
                'npsn' => '10648920',
                'address' => 'Jl. Pendidikan Islam Terpadu No. 12, Bukit Besar, Kota Palembang',
                'phone' => '(0711) 5678901',
                'email' => 'sdit@smartedu.sch.id',
                'website' => 'https://sdit.smartedu.sch.id',
                'principal_name' => 'Ustadz H. Ahmad Fauzi, S.Pd.I., M.Pd.',
                'principal_nip' => '198205142008011005',
                'quran_coordinator_name' => 'Ustadz Muhammad Hafizh, Lc., Al-Hafizh',
                'quran_coordinator_nip' => '198709122014021003',
                'report_city' => 'Palembang',
                'report_date' => '2025-12-20',
                'print_settings' => [
                    'show_stamp' => true,
                    'show_signature' => true,
                    'paper_size' => 'A4',
                    'orientation' => 'portrait',
                    'header_type' => 'text_and_logo'
                ]
            ]
        );

        $smpit = SchoolUnit::updateOrCreate(
            ['code' => 'smpit'],
            [
                'name' => 'SMPIT SmartEdu Palembang',
                'level' => 'smp',
                'npsn' => '10648921',
                'address' => 'Jl. Pendidikan Islam Terpadu No. 14, Bukit Besar, Kota Palembang',
                'phone' => '(0711) 5678902',
                'email' => 'smpit@smartedu.sch.id',
                'website' => 'https://smpit.smartedu.sch.id',
                'principal_name' => 'Ustadzah Dra. Hj. Siti Rahmah, M.Pd.I.',
                'principal_nip' => '198403212009022006',
                'quran_coordinator_name' => 'Ustadz Abdurrahman Al-Hafizh, S.Ag.',
                'quran_coordinator_nip' => '198901152015031002',
                'report_city' => 'Palembang',
                'report_date' => '2025-12-20',
                'print_settings' => [
                    'show_stamp' => true,
                    'show_signature' => true,
                    'paper_size' => 'A4',
                    'orientation' => 'portrait',
                    'header_type' => 'text_and_logo'
                ]
            ]
        );

        $tkit = SchoolUnit::updateOrCreate(
            ['code' => 'tkit'],
            [
                'name' => 'TKIT SmartEdu Palembang',
                'level' => 'tk',
                'npsn' => '10648919',
                'address' => 'Jl. Pendidikan Islam Terpadu No. 10, Kota Palembang',
                'principal_name' => 'Ustadzah Nurul Hidayati, S.Pd.AUD',
                'report_city' => 'Palembang',
                'report_date' => '2025-12-20',
            ]
        );

        $smait = SchoolUnit::updateOrCreate(
            ['code' => 'smait'],
            [
                'name' => 'SMAIT SmartEdu Palembang',
                'level' => 'sma',
                'npsn' => '10648922',
                'address' => 'Jl. Pendidikan Islam Terpadu No. 16, Kota Palembang',
                'principal_name' => 'Ustadz Dr. H. Sulaiman, M.Si.',
                'report_city' => 'Palembang',
                'report_date' => '2025-12-20',
            ]
        );

        // 3. Tahun Ajaran
        $aySdit = AcademicYear::firstOrCreate([
            'school_unit_id' => $sdit->id,
            'name' => '2025/2026',
            'semester' => 'ganjil',
        ], ['is_active' => true]);

        $aySmpit = AcademicYear::firstOrCreate([
            'school_unit_id' => $smpit->id,
            'name' => '2025/2026',
            'semester' => 'ganjil',
        ], ['is_active' => true]);

        // 4. Setup Kriteria Al-Qur'an (Metode Wafa) untuk SDIT & SMPIT
        $wafaCriteriaList = [
            ['cat' => 'tahsin', 'code' => 'tilawah', 'name' => 'Kelancaran Tilawah Wafa & Mura\'atul Kalimah', 'desc' => 'Kelancaran membaca sesuai kaidah talaqqi Metode Wafa, tartil, dan tidak terputus-putus.', 'order' => 1],
            ['cat' => 'tahsin', 'code' => 'makhraj', 'name' => 'Makharijul Huruf & Sifatul Huruf', 'desc' => 'Ketepatan keluarnya huruf dari makhrajnya dan kesempurnaan sifat huruf.', 'order' => 2],
            ['cat' => 'tahsin', 'code' => 'tajwid', 'name' => 'Hukum Tajwid & Mad Ghunnah', 'desc' => 'Penerapan hukum nun sukun, mim sukun, mad thobi\'i/far\'i, idgham, dan ghunnah.', 'order' => 3],
            ['cat' => 'tahsin', 'code' => 'nada_wafa', 'name' => 'Irama / Nada Khas Wafa (Hijaz)', 'desc' => 'Penerapan 3 nada khas Wafa (naik, datar, turun) secara harmonis dan tertib.', 'order' => 4],
            ['cat' => 'tahsin', 'code' => 'adab', 'name' => 'Adab & Kesopanan Tilawah', 'desc' => 'Posisi duduk yang tegap, memegang buku/mushaf dengan ta\'zhim, dan khusyuk.', 'order' => 5],
            ['cat' => 'tahfidz', 'code' => 'ziyadah', 'name' => 'Kelancaran Hafalan Baru (Ziyadah)', 'desc' => 'Kelancaran dan kekuatan hafalan ayat-ayat baru.', 'order' => 6],
            ['cat' => 'tahfidz', 'code' => 'murajaah', 'name' => 'Kerapihan Pengulangan (Muraja\'ah)', 'desc' => 'Kemantapan mengingat hafalan lama agar tetap mutqin.', 'order' => 7],
            ['cat' => 'tahfidz', 'code' => 'tasmi', 'name' => 'Ujian Tasmi\' Sekali Duduk', 'desc' => 'Kelulusan memperdengarkan hafalan secara utuh dihadapan ustadz penguji.', 'order' => 8],
        ];

        foreach ([$sdit, $smpit] as $unit) {
            foreach ($wafaCriteriaList as $item) {
                QuranCriterion::updateOrCreate([
                    'school_unit_id' => $unit->id,
                    'code' => $item['code'],
                ], [
                    'category' => $item['cat'],
                    'name' => $item['name'],
                    'description' => $item['desc'],
                    'order_number' => $item['order'],
                ]);
            }
        }

        // 5. Setup 7 Indikator SKL Karakter JSIT
        $sklIndicators = [
            ['code' => 'SKL_1', 'name' => '1. Salimul Aqidah (Akidah yang Lurus)', 'ind' => 'Meyakini rukun iman dengan benar, menjauhi perbuatan syirik, senantiasa berdzikir dan berdo\'a kepada Allah.', 'order' => 1],
            ['code' => 'SKL_2', 'name' => '2. Shahihul Ibadah (Ibadah yang Benar)', 'ind' => 'Melaksanakan shalat fardhu 5 waktu berjamaah, tertib wudhu, gemar shalat Dhuha dan puasa sunnah.', 'order' => 2],
            ['code' => 'SKL_3', 'name' => '3. Matinul Khuluq (Akhlak yang Mulia)', 'ind' => 'Berbakti kepada orang tua, santun kepada guru, berkata jujur, menjauhi ghibah/bullying, dan gemar menebar salam.', 'order' => 3],
            ['code' => 'SKL_4', 'name' => '4. Qowiyyul Jismi (Jasmani Sehat & Bugar)', 'ind' => 'Menjaga kebersihan diri dan lingkungan, gemar berolahraga, makan makanan halal & thayyib, serta tidur tepat waktu.', 'order' => 4],
            ['code' => 'SKL_5', 'name' => '5. Mutsaqqoful Fikri (Cerdas & Berwawasan)', 'ind' => 'Gemar membaca/literasi, aktif bertanya di kelas, rajin menyelesaikan tugas, dan berpikir kritis.', 'order' => 5],
            ['code' => 'SKL_6', 'name' => '6. Mujahidun Linafsihi & Mandiri', 'ind' => 'Mampu mengendalikan emosi, mandiri merapikan barang pribadi, disiplin mengelola waktu belajar dan bermain.', 'order' => 6],
            ['code' => 'SKL_7', 'name' => '7. Nafi\'un Lighairihi (Bermanfaat bagi Sesama)', 'ind' => 'Suka menolong teman yang kesulitan, aktif berinfaq Jum\'at, peduli kelestarian alam, dan ramah.', 'order' => 7],
        ];

        foreach ([$sdit, $smpit] as $unit) {
            foreach ($sklIndicators as $skl) {
                CharacterIndicator::updateOrCreate([
                    'school_unit_id' => $unit->id,
                    'standard_code' => $skl['code'],
                ], [
                    'standard_name' => $skl['name'],
                    'indicator_name' => $skl['ind'],
                    'order_number' => $skl['order'],
                ]);
            }
        }

        // 6. Mata Pelajaran SDIT
        $sditSubjects = [
            ['code' => 'PAI', 'name' => 'Pendidikan Agama Islam & Budi Pekerti', 'cat' => 'nasional', 'order' => 1],
            ['code' => 'PKN', 'name' => 'Pendidikan Pancasila', 'cat' => 'nasional', 'order' => 2],
            ['code' => 'BIND', 'name' => 'Bahasa Indonesia', 'cat' => 'nasional', 'order' => 3],
            ['code' => 'MTK', 'name' => 'Matematika', 'cat' => 'nasional', 'order' => 4],
            ['code' => 'IPAS', 'name' => 'Ilmu Pengetahuan Alam dan Sosial (IPAS)', 'cat' => 'nasional', 'order' => 5],
            ['code' => 'SNRP', 'name' => 'Seni Rupa & Prakarya', 'cat' => 'nasional', 'order' => 6],
            ['code' => 'PJOK', 'name' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'cat' => 'nasional', 'order' => 7],
            ['code' => 'ARAB', 'name' => 'Bahasa Arab Terpadu (Kekhasan SIT)', 'cat' => 'diniyah_sit', 'order' => 8],
            ['code' => 'FIQH', 'name' => 'Fiqih Ibadah Praktis & Sirah Nabawiyah', 'cat' => 'diniyah_sit', 'order' => 9],
            ['code' => 'BING', 'name' => 'Bahasa Inggris', 'cat' => 'muatan_lokal', 'order' => 10],
        ];

        foreach ($sditSubjects as $s) {
            $sub = Subject::updateOrCreate([
                'school_unit_id' => $sdit->id,
                'code' => $s['code'],
            ], [
                'name' => $s['name'],
                'category' => $s['cat'],
                'order_number' => $s['order'],
            ]);

            // Buat TP (Tujuan Pembelajaran) untuk Kelas 4
            LearningObjective::firstOrCreate([
                'subject_id' => $sub->id,
                'grade_level' => 4,
                'semester' => 'ganjil',
                'code' => 'TP 4.1',
            ], [
                'description' => "Memahami dan mendemonstrasikan konsep dasar materi {$sub->name} secara aplikatif."
            ]);

            LearningObjective::firstOrCreate([
                'subject_id' => $sub->id,
                'grade_level' => 4,
                'semester' => 'ganjil',
                'code' => 'TP 4.2',
            ], [
                'description' => "Menyelesaikan permasalahan kontekstual dan studi kasus yang berkaitan dengan {$sub->name}."
            ]);
        }

        // 7. Rombel & Siswa SDIT (Kelas 4 A - Shalahuddin Al-Ayyubi)
        $classSdit = Classroom::firstOrCreate([
            'school_unit_id' => $sdit->id,
            'academic_year_id' => $aySdit->id,
            'name' => '4 A - Shalahuddin Al-Ayyubi',
        ], [
            'grade_level' => 4,
            'phase' => 'B',
            'homeroom_teacher_id' => $admin->id,
        ]);

        $studentsData = [
            [
                'nis' => '250401',
                'nisn' => '0145892301',
                'full_name' => 'Muhammad Fatih Al-Ghazali',
                'nickname' => 'Fatih',
                'gender' => 'L',
                'birth_place' => 'Palembang',
                'birth_date' => '2015-05-12',
                'parent_name' => 'Ir. Hendra Gunawan',
                'parent_phone' => '081271234567',
                'address' => 'Komplek Griya Ceria Blok B No. 5, Palembang',
            ],
            [
                'nis' => '250402',
                'nisn' => '0145892302',
                'full_name' => 'Aisyah Humaira Putri',
                'nickname' => 'Aisyah',
                'gender' => 'P',
                'birth_place' => 'Palembang',
                'birth_date' => '2015-08-20',
                'parent_name' => 'Drs. Ridwan Hakim',
                'parent_phone' => '081373456789',
                'address' => 'Jl. Angkatan 45 No. 88, Palembang',
            ],
            [
                'nis' => '250403',
                'nisn' => '0145892303',
                'full_name' => 'Zaid bin Haritsah Pratama',
                'nickname' => 'Zaid',
                'gender' => 'L',
                'birth_place' => 'Ogan Ilir',
                'birth_date' => '2015-02-14',
                'parent_name' => 'H. Bambang Irawan',
                'parent_phone' => '082182345678',
                'address' => 'Jl. Demang Lebar Daun No. 21, Palembang',
            ]
        ];

        foreach ($studentsData as $stData) {
            $student = Student::firstOrCreate([
                'school_unit_id' => $sdit->id,
                'nis' => $stData['nis'],
            ], $stData);

            $cs = ClassroomStudent::firstOrCreate([
                'classroom_id' => $classSdit->id,
                'student_id' => $student->id,
                'academic_year_id' => $aySdit->id,
            ], [
                'attendance_sakit' => 1,
                'attendance_izin' => 1,
                'attendance_alpa' => 0,
                'homeroom_notes' => 'Ananda Fatih memiliki keteladanan yang baik dalam sholat berjamaah dan sangat rajin bertanya di kelas. Pertahankan prestasinya!',
                'physical_height' => 134,
                'physical_weight' => 30,
                'physical_hearing' => 'Normal / Sangat Baik',
                'physical_vision' => 'Normal',
                'physical_dental' => 'Bersih & Terawat',
                'status' => 'aktif',
            ]);

            // Isi Nilai Akademik
            $subjects = Subject::where('school_unit_id', $sdit->id)->get();
            foreach ($subjects as $idx => $subj) {
                $tps = LearningObjective::where('subject_id', $subj->id)->get();
                $formative = [];
                $base = 85 + ($idx % 7);
                foreach ($tps as $t) {
                    $formative[$t->id] = min(98, $base + rand(-3, 6));
                }

                $ag = AcademicGrade::firstOrCreate([
                    'classroom_student_id' => $cs->id,
                    'subject_id' => $subj->id,
                ], [
                    'formative_scores' => $formative,
                    'score_sumative_material' => $base + 2,
                    'score_sumative_final' => $base + 4,
                    'final_grade' => $base + 3,
                ]);

                // Auto narrative
                $narr = $ag->generateNarrative();
                $ag->update([
                    'highest_achievement' => $narr['highest'],
                    'lowest_achievement' => $narr['lowest'],
                ]);
            }

            // Isi Nilai Quran Wafa
            $wafaScores = [];
            $criteria = QuranCriterion::where('school_unit_id', $sdit->id)->where('category', 'tahsin')->get();
            foreach ($criteria as $c) {
                $wafaScores[$c->id] = rand(88, 95);
            }

            QuranGrade::firstOrCreate([
                'classroom_student_id' => $cs->id,
            ], [
                'tahsin_method' => 'Wafa',
                'tahsin_level' => 'Buku Wafa 4 Hal. 35 (Irama Hijaz)',
                'tahsin_scores' => $wafaScores,
                'tahsin_final_score' => 91.5,
                'tahsin_predicate' => 'Mumtaz (Sangat Baik)',
                'tahsin_notes' => 'Alhamdulillah, ananda melafalkan makhraj huruf dengan fasih dan irama lagu Wafa telah terbentuk sangat merdu.',
                'tahfidz_target' => 'Juz 30 (An-Naba\' s/d An-Nas)',
                'tahfidz_achievement' => 'Tuntas Juz 30 (Al-A\'la s/d An-Nas)',
                'tahfidz_score' => 93.0,
                'tahfidz_predicate' => 'Mutqin',
                'tasmi_exam_result' => 'Lulus Tasmi\' 1/2 Juz Sekali Duduk dengan Predikat Mumtaz',
                'tahfidz_notes' => 'Hafalan sangat kuat dan lancar. Siap melanjutkan ziyadah hafalan ke Juz 29.',
                'examiner_teacher_id' => $admin->id,
            ]);

            // Isi Nilai Karakter JSIT
            $charScores = [];
            $indics = CharacterIndicator::where('school_unit_id', $sdit->id)->get();
            foreach ($indics as $ind) {
                $charScores[$ind->id] = 'BSB'; // Berkembang Sangat Baik
            }

            CharacterGrade::firstOrCreate([
                'classroom_student_id' => $cs->id,
            ], [
                'indicator_scores' => $charScores,
                'mutabaah_sholat_fardhu' => 'Selalu Berjamaah di Masjid / Shaf Terdepan',
                'mutabaah_sholat_dhuha' => 'Rutin 4 Rakaat Setiap Pagi',
                'mutabaah_tilawah' => 'Istiqomah 1/2 Juz per Hari dengan Metode Wafa',
                'mutabaah_infaq' => 'Rutin Berinfaq Setiap Hari Jum\'at',
                'bpi_mentor_notes' => 'Ananda menunjukkan komitmen yang luar biasa dalam memimpin adzan dan shalat berjamaah. Menjadi teladan akhlak mulia bagi teman sekelas.',
            ]);

            // Ekstrakurikuler
            ExtracurricularGrade::firstOrCreate([
                'classroom_student_id' => $cs->id,
                'activity_name' => 'Pramuka SIT (Sako Pramuka SIT)',
            ], [
                'predicate' => 'Sangat Baik',
                'description' => 'Aktif dalam baris-berbaris, pionering, dan leadership camp SIT.',
            ]);

            ExtracurricularGrade::firstOrCreate([
                'classroom_student_id' => $cs->id,
                'activity_name' => 'Panahan Tradisional (Archery)',
            ], [
                'predicate' => 'Sangat Baik',
                'description' => 'Menguasai teknik dasar kuda-kuda dan akurasi sasaran panahan 10 meter.',
            ]);
        }
    }
}
