<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Leger Nilai Kelas {{ $classroom->name }} - {{ $unit->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0f172a;
            background: #f8fafc;
        }
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .print-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="p-4 md:p-8">

    <!-- Print Action Bar -->
    <div class="no-print max-w-7xl mx-auto mb-4 p-4 bg-slate-900 text-white rounded-2xl flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-3">
            <span class="text-xl">📊</span>
            <div>
                <h3 class="font-bold text-sm text-white">Leger Nilai Kelas: {{ $classroom->name }}</h3>
                <p class="text-xs text-slate-400">{{ $unit->name }} • Tahun Ajaran {{ $classroom->academicYear->name }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-xs shadow transition flex items-center gap-1.5">
                <span>🖨️ Cetak Leger (Landscape)</span>
            </button>
            <button onclick="window.close()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition">
                Tutup
            </button>
        </div>
    </div>

    <div class="print-container max-w-7xl mx-auto bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-slate-900 text-[10px]">
        
        <!-- Header Leger -->
        <div class="text-center border-b-2 border-slate-900 pb-2 mb-3">
            <p class="font-bold text-slate-500 uppercase tracking-widest text-[9px]">YAYASAN PENDIDIKAN ISLAM TERPADU • {{ $unit->name }}</p>
            <h1 class="text-base font-black uppercase text-slate-900">LEGER NILAI HASIL BELAJAR SISWA</h1>
            <p class="text-[10px] text-slate-600 font-semibold">
                Kelas: {{ $classroom->name }} • Fase: {{ $classroom->phase }} • Semester: {{ ucfirst($classroom->academicYear->semester) }} • Tahun Ajaran: {{ $classroom->academicYear->name }}
            </p>
        </div>

        <!-- Tabel Leger Besar -->
        <div class="overflow-x-auto mb-4">
            <table class="w-full border-collapse border border-slate-300 text-[9.5px]">
                <thead>
                    <tr class="bg-slate-100 text-slate-800 font-extrabold text-center uppercase">
                        <th class="border border-slate-300 py-1.5 px-1 w-6" rowspan="2">No</th>
                        <th class="border border-slate-300 py-1.5 px-2 w-16" rowspan="2">NIS</th>
                        <th class="border border-slate-300 py-1.5 px-2 w-44 text-left" rowspan="2">Nama Santri</th>
                        <th class="border border-slate-300 py-1 px-1 bg-teal-50" colspan="{{ $subjects->count() }}">Mata Pelajaran (Nilai Akhir)</th>
                        <th class="border border-slate-300 py-1 px-1 bg-emerald-50" colspan="2">Al-Qur'an (Wafa)</th>
                        <th class="border border-slate-300 py-1.5 px-1.5 w-12" rowspan="2">Rata Rata</th>
                        <th class="border border-slate-300 py-1 px-1 bg-slate-50" colspan="3">Presensi</th>
                    </tr>
                    <tr class="bg-slate-50 font-bold text-center text-[8.5px]">
                        @foreach($subjects as $s)
                            <th class="border border-slate-300 py-1 px-1 w-10" title="{{ $s->name }}">{{ $s->code }}</th>
                        @endforeach
                        <th class="border border-slate-300 py-1 px-1 w-12 bg-emerald-50/50">Tahsin</th>
                        <th class="border border-slate-300 py-1 px-1 w-12 bg-emerald-50/50">Tahfidz</th>
                        <th class="border border-slate-300 py-1 px-1 w-6">S</th>
                        <th class="border border-slate-300 py-1 px-1 w-6">I</th>
                        <th class="border border-slate-300 py-1 px-1 w-6">A</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classroom->classroomStudents as $idx => $cs)
                        @php
                            $gradesBySubj = $cs->academicGrades->keyBy('subject_id');
                            $qGrade = $cs->quranGrade;
                        @endphp
                        <tr class="hover:bg-slate-50 transition text-center">
                            <td class="border border-slate-300 py-1.5 px-1 font-bold">{{ $idx + 1 }}</td>
                            <td class="border border-slate-300 py-1.5 px-1 font-mono">{{ $cs->student->nis ?? '-' }}</td>
                            <td class="border border-slate-300 py-1.5 px-2 text-left font-bold text-slate-900 truncate max-w-xs">
                                {{ $cs->student->full_name }}
                            </td>

                            <!-- Scores per subject -->
                            @foreach($subjects as $s)
                                @php $grade = $gradesBySubj->get($s->id); @endphp
                                <td class="border border-slate-300 py-1 px-1">
                                    {{ $grade ? ($grade->final_grade ?? '-') : '-' }}
                                </td>
                            @endforeach

                            <!-- Quran Scores -->
                            <td class="border border-slate-300 py-1 px-1 font-semibold text-teal-900 bg-teal-50/30">
                                {{ $qGrade ? ($qGrade->tahsin_final_score ?? '-') : '-' }}
                            </td>
                            <td class="border border-slate-300 py-1 px-1 font-semibold text-emerald-900 bg-emerald-50/30">
                                {{ $qGrade ? ($qGrade->tahfidz_score ?? '-') : '-' }}
                            </td>

                            <!-- Average -->
                            <td class="border border-slate-300 py-1 px-1 font-black text-slate-900 bg-slate-100">
                                {{ $cs->calculateAverageGrade() }}
                            </td>

                            <!-- Attendance -->
                            <td class="border border-slate-300 py-1 px-1">{{ $cs->attendance_sakit }}</td>
                            <td class="border border-slate-300 py-1 px-1">{{ $cs->attendance_izin }}</td>
                            <td class="border border-slate-300 py-1 px-1">{{ $cs->attendance_alpa }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tanda Tangan Leger -->
        <div class="grid grid-cols-2 gap-8 text-center text-[10px] pt-3">
            <div>
                <p class="text-slate-500">Wali Kelas,</p>
                <div class="h-14 flex items-center justify-center">
                    @if(!empty($classroom->homeroomTeacher->signature_path) && ($unit->print_settings['show_signature'] ?? true))
                        <img src="{{ asset($classroom->homeroomTeacher->signature_path) }}" class="h-10 w-auto object-contain">
                    @endif
                </div>
                <p class="font-bold border-b border-slate-900 inline-block px-4 pb-0.5">
                    {{ $classroom->homeroomTeacher->name ?? 'Wali Kelas' }}
                </p>
                <p class="text-[9px] text-slate-500">NIP: {{ $classroom->homeroomTeacher->nip ?? '-' }}</p>
            </div>

            <div>
                <p class="text-slate-500">{{ $unit->report_city }}, {{ optional($unit->report_date)->translatedFormat('d F Y') ?? date('d F Y') }}</p>
                <p class="text-slate-500 font-semibold">Kepala Sekolah,</p>
                <div class="h-14 flex items-center justify-center relative">
                    @if($unit->principal_signature_path && ($unit->print_settings['show_signature'] ?? true))
                        <img src="{{ asset($unit->principal_signature_path) }}" class="h-12 w-auto object-contain z-10">
                    @endif
                    @if($unit->stamp_path && ($unit->print_settings['show_stamp'] ?? true))
                        <img src="{{ asset($unit->stamp_path) }}" class="h-12 w-auto object-contain absolute opacity-80 -rotate-6 z-20 pointer-events-none">
                    @endif
                </div>
                <p class="font-bold border-b border-slate-900 inline-block px-4 pb-0.5">
                    {{ $unit->principal_name ?? 'Kepala Sekolah' }}
                </p>
                <p class="text-[9px] text-slate-500">NIP: {{ $unit->principal_nip ?? '-' }}</p>
            </div>
        </div>

    </div>

</body>
</html>
