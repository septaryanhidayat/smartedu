<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>E-Rapor Terpadu SIT (Bundle) - {{ $cs->student->full_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0f172a;
            background: #f1f5f9;
        }
        @page {
            size: A4;
            margin: 12mm 15mm;
        }
        .page-sheet {
            page-break-after: always;
            break-after: page;
        }
        .page-sheet:last-child {
            page-break-after: auto;
            break-after: auto;
        }
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .page-sheet {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                width: 100% !important;
                margin: 0 0 10mm 0 !important;
            }
        }
    </style>
</head>
<body class="p-6 md:p-10">

    <!-- Print Action Bar -->
    <div class="no-print max-w-4xl mx-auto mb-6 p-4 bg-slate-900 text-white rounded-2xl flex items-center justify-between shadow-lg sticky top-4 z-50">
        <div class="flex items-center gap-3">
            <span class="text-2xl">📑</span>
            <div>
                <h3 class="font-bold text-sm text-white">Pratinjau Cetak Gabungan (All-in-One E-Rapor)</h3>
                <p class="text-xs text-emerald-400 font-semibold">{{ $cs->student->full_name }} • {{ $cs->classroom->name }} (4 Halaman Terpadu)</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-extrabold text-xs shadow-lg transition flex items-center gap-1.5">
                <span>🖨️ Cetak Seluruh Halaman / Simpan PDF</span>
            </button>
            <button onclick="window.close()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition">
                Tutup
            </button>
        </div>
    </div>

    <div class="max-w-4xl mx-auto space-y-8">

        <!-- ========================================================================= -->
        <!-- HALAMAN 1: COVER RAPOR RESMI SIT -->
        <!-- ========================================================================= -->
        <div class="page-sheet bg-white p-12 md:p-16 rounded-3xl border border-slate-200 shadow-sm text-center flex flex-col justify-between min-h-[950px]">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">YAYASAN PENDIDIKAN ISLAM TERPADU</p>
                <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight mt-2">{{ $unit->name }}</h1>
                <div class="w-16 h-1 bg-emerald-600 mx-auto my-3 rounded-full"></div>
                <p class="text-xs text-slate-600">SEKOLAH ISLAM TERPADU (SIT) BERBASIS AL-QUR'AN DAN KARAKTER</p>
            </div>

            <div class="my-8">
                <img src="{{ $unit->logo_url }}" alt="Logo" class="w-36 h-36 mx-auto object-contain drop-shadow-md">
                <div class="mt-8">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-800 font-black text-xs border border-emerald-200 tracking-wider uppercase">
                        BUKU LAPORAN HASIL BELAJAR PESERTA DIDIK
                    </span>
                    <h2 class="text-xl font-black text-slate-900 mt-3">E-RAPOR TERPADU SIT</h2>
                    <p class="text-xs text-slate-500 mt-1">Kurikulum Merdeka Kemendikbudristek • Metode Wafa • Standar Mutu JSIT</p>
                </div>
            </div>

            <div class="max-w-md mx-auto w-full p-6 bg-slate-50 rounded-2xl border border-slate-200 text-left text-xs space-y-2.5">
                <div class="flex"><span class="w-32 text-slate-500 font-semibold">Nama Peserta Didik</span><span class="font-bold text-slate-900">: {{ $cs->student->full_name }}</span></div>
                <div class="flex"><span class="w-32 text-slate-500 font-semibold">NIS / NISN</span><span class="font-bold text-slate-900">: {{ $cs->student->nis ?? '-' }} / {{ $cs->student->nisn ?? '-' }}</span></div>
                <div class="flex"><span class="w-32 text-slate-500 font-semibold">Rombongan Belajar</span><span class="font-bold text-slate-900">: {{ $cs->classroom->name }}</span></div>
                <div class="flex"><span class="w-32 text-slate-500 font-semibold">Tahun Pelajaran</span><span class="font-bold text-slate-900">: {{ $cs->academicYear->name }}</span></div>
                <div class="flex"><span class="w-32 text-slate-500 font-semibold">Semester</span><span class="font-bold text-slate-900">: {{ ucfirst($cs->academicYear->semester) }}</span></div>
            </div>

            <div class="text-[11px] text-slate-500">
                <p class="font-bold text-slate-800">{{ $unit->name }}</p>
                <p>{{ $unit->address }}</p>
                <p>KOTA {{ strtoupper($unit->report_city) }}</p>
            </div>
        </div>


        <!-- ========================================================================= -->
        <!-- HALAMAN 2: RAPOR AKADEMIK (KURIKULUM MERDEKA) -->
        <!-- ========================================================================= -->
        <div class="page-sheet bg-white p-8 rounded-3xl border border-slate-200 shadow-sm text-slate-900 text-xs">
            <!-- Header Kop Surat -->
            @if($unit->letterhead_path && file_exists(public_path($unit->letterhead_path)))
                <div class="mb-4 text-center">
                    <img src="{{ asset($unit->letterhead_path) }}" alt="Kop Surat" class="w-full max-h-24 object-contain mx-auto">
                </div>
            @else
                <div class="border-b-2 border-slate-900 pb-2 mb-3 flex items-center gap-3">
                    <img src="{{ $unit->logo_url }}" alt="Logo" class="w-14 h-14 object-contain">
                    <div class="flex-1 text-center pr-10">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">YAYASAN PENDIDIKAN ISLAM TERPADU</p>
                        <h2 class="text-base font-black text-slate-900 uppercase">{{ $unit->name }}</h2>
                        <p class="text-[9px] text-slate-600">{{ $unit->address }} • Telp: {{ $unit->phone ?? '-' }}</p>
                    </div>
                </div>
            @endif

            <div class="text-center mb-3">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">BAGIAN I: LAPORAN HASIL BELAJAR (RAPOR AKADEMIK)</h3>
                <p class="text-[10px] font-bold text-slate-600">KURIKULUM MERDEKA</p>
            </div>

            <!-- Identitas Siswa Singkat -->
            <div class="grid grid-cols-2 gap-x-4 gap-y-1 mb-3 p-2.5 bg-slate-50 rounded-xl border border-slate-200 text-[10px]">
                <div><span class="text-slate-500">Nama:</span> <strong class="text-slate-900">{{ $cs->student->full_name }}</strong></div>
                <div><span class="text-slate-500">Kelas:</span> <strong class="text-slate-900">{{ $cs->classroom->name }} (Fase {{ $cs->classroom->phase }})</strong></div>
                <div><span class="text-slate-500">NIS / NISN:</span> <strong class="text-slate-900">{{ $cs->student->nis ?? '-' }} / {{ $cs->student->nisn ?? '-' }}</strong></div>
                <div><span class="text-slate-500">Semester:</span> <strong class="text-slate-900">{{ ucfirst($cs->academicYear->semester) }} • {{ $cs->academicYear->name }}</strong></div>
            </div>

            <!-- Tabel Nilai Mapel -->
            <table class="w-full border-collapse border border-slate-300 text-[10px] mb-3">
                <thead>
                    <tr class="bg-slate-100 text-slate-800 font-extrabold text-center uppercase">
                        <th class="border border-slate-300 py-1.5 px-1.5 w-6">No</th>
                        <th class="border border-slate-300 py-1.5 px-2.5 w-44 text-left">Mata Pelajaran</th>
                        <th class="border border-slate-300 py-1.5 px-1.5 w-12">Nilai</th>
                        <th class="border border-slate-300 py-1.5 px-2.5">Capaian Kompetensi Pembelajaran (TP)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($academicGrades as $grade)
                        <tr>
                            <td class="border border-slate-300 py-1.5 px-1.5 text-center font-bold">{{ $no++ }}</td>
                            <td class="border border-slate-300 py-1.5 px-2.5 font-bold text-slate-900">
                                {{ $grade->subject->name }}
                                @if($grade->subject->category == 'diniyah_sit')
                                    <span class="text-[8px] font-semibold text-emerald-700 block">* Diniyah SIT</span>
                                @endif
                            </td>
                            <td class="border border-slate-300 py-1.5 px-1.5 text-center font-black bg-slate-50">{{ $grade->final_grade ?? '-' }}</td>
                            <td class="border border-slate-300 py-1.5 px-2.5 space-y-0.5 leading-tight text-[9.5px]">
                                @if($grade->highest_achievement)
                                    <p><strong class="text-emerald-800">Capaian Tertinggi:</strong> {{ $grade->highest_achievement }}</p>
                                @endif
                                @if($grade->lowest_achievement)
                                    <p><strong class="text-amber-800">Perlu Bimbingan:</strong> {{ $grade->lowest_achievement }}</p>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Ekstra & Presensi -->
            <div class="grid grid-cols-2 gap-3 mb-3 text-[10px]">
                <div>
                    <h4 class="font-extrabold uppercase text-[9px] text-slate-700 mb-1">Kegiatan Ekstrakurikuler</h4>
                    <table class="w-full border-collapse border border-slate-300">
                        @foreach($cs->extracurricularGrades as $ekstra)
                            <tr>
                                <td class="border border-slate-300 py-1 px-2 font-bold">{{ $ekstra->activity_name }}</td>
                                <td class="border border-slate-300 py-1 px-2 text-center font-black text-emerald-800">{{ $ekstra->predicate }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                <div>
                    <h4 class="font-extrabold uppercase text-[9px] text-slate-700 mb-1">Presensi & Kesehatan</h4>
                    <div class="grid grid-cols-3 gap-1 text-center font-semibold">
                        <div class="p-1 bg-slate-50 border border-slate-300 rounded">Sakit: <strong>{{ $cs->attendance_sakit }}</strong></div>
                        <div class="p-1 bg-slate-50 border border-slate-300 rounded">Izin: <strong>{{ $cs->attendance_izin }}</strong></div>
                        <div class="p-1 bg-slate-50 border border-slate-300 rounded">Alpa: <strong>{{ $cs->attendance_alpa }}</strong></div>
                    </div>
                </div>
            </div>

            <!-- Catatan Wali Kelas -->
            <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-300 text-[10px] mb-3">
                <strong class="text-slate-800 uppercase block mb-0.5">Catatan Wali Kelas:</strong>
                <p class="italic text-slate-700">"{{ $cs->homeroom_notes ?? 'Tingkatkan terus semangat belajar dan pertahankan akhlak mulia dalam pergaulan.' }}"</p>
            </div>

            <!-- Tanda Tangan Ringkas -->
            <div class="grid grid-cols-3 gap-2 text-center text-[10px] pt-2 border-t border-slate-200">
                <div>
                    <p class="text-slate-500">Orang Tua / Wali</p>
                    <div class="h-12"></div>
                    <p class="font-bold border-b border-slate-400 inline-block px-4">................................</p>
                </div>
                <div>
                    <p class="text-slate-500">Wali Kelas</p>
                    <div class="h-12 flex items-center justify-center">
                        @if(!empty($cs->classroom->homeroomTeacher->signature_path) && ($unit->print_settings['show_signature'] ?? true))
                            <img src="{{ asset($cs->classroom->homeroomTeacher->signature_path) }}" class="h-10 w-auto object-contain">
                        @endif
                    </div>
                    <p class="font-bold border-b border-slate-900 inline-block px-2">{{ $cs->classroom->homeroomTeacher->name ?? 'Wali Kelas' }}</p>
                </div>
                <div>
                    <p class="text-slate-500">{{ $unit->report_city }}, {{ optional($unit->report_date)->translatedFormat('d F Y') ?? date('d F Y') }}</p>
                    <div class="h-12 flex items-center justify-center relative">
                        @if($unit->principal_signature_path && ($unit->print_settings['show_signature'] ?? true))
                            <img src="{{ asset($unit->principal_signature_path) }}" class="h-12 w-auto object-contain z-10">
                        @endif
                        @if($unit->stamp_path && ($unit->print_settings['show_stamp'] ?? true))
                            <img src="{{ asset($unit->stamp_path) }}" class="h-12 w-auto object-contain absolute opacity-80 -rotate-6 z-20 pointer-events-none">
                        @endif
                    </div>
                    <p class="font-bold border-b border-slate-900 inline-block px-2">{{ $unit->principal_name ?? 'Kepala Sekolah' }}</p>
                </div>
            </div>
        </div>


        <!-- ========================================================================= -->
        <!-- HALAMAN 3: RAPOR AL-QUR'AN (METODE WAFA & TAHFIDZ) -->
        <!-- ========================================================================= -->
        <div class="page-sheet bg-white p-8 rounded-3xl border border-slate-200 shadow-sm text-slate-900 text-xs">
            <div class="text-center mb-3">
                <span class="px-3 py-1 rounded-full bg-teal-50 text-teal-800 font-extrabold text-[10px] border border-teal-200 uppercase">
                    METODE WAFA BELAJAR AL-QUR'AN (OTAK KANAN)
                </span>
                <h3 class="text-sm font-black uppercase tracking-wider text-slate-900 mt-2">BAGIAN II: LAPORAN CAPAIAN AL-QUR'AN & TAHFIDZ</h3>
                <p class="text-[10px] text-slate-500">{{ $unit->name }} • {{ $cs->student->full_name }} ({{ $cs->classroom->name }})</p>
            </div>

            <!-- Tahsin Wafa -->
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-extrabold uppercase text-[11px] text-teal-950">A. Tahsin Al-Qur'an (Metode Wafa)</h4>
                    <span class="text-[10px] font-bold bg-slate-100 px-2.5 py-0.5 rounded border border-slate-200">
                        Capaian: <strong class="text-teal-900">{{ $quranGrade->tahsin_level ?? 'Buku Wafa 3' }}</strong>
                    </span>
                </div>

                <table class="w-full border-collapse border border-slate-300 text-[10px] mb-2">
                    <thead>
                        <tr class="bg-teal-50 text-teal-950 font-extrabold text-center uppercase">
                            <th class="border border-slate-300 py-1.5 px-2 w-8">No</th>
                            <th class="border border-slate-300 py-1.5 px-3 text-left">Komponen Penilaian Standar Wafa</th>
                            <th class="border border-slate-300 py-1.5 px-2 w-20">Nilai (0-100)</th>
                            <th class="border border-slate-300 py-1.5 px-3 text-left">Keterangan Mutu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $tScores = $quranGrade->tahsin_scores ?? [];
                            $noQ = 1;
                        @endphp
                        @foreach($quranCriteria as $qc)
                            <tr>
                                <td class="border border-slate-300 py-1.5 px-2 text-center font-bold">{{ $noQ++ }}</td>
                                <td class="border border-slate-300 py-1.5 px-3 font-bold text-slate-900">{{ $qc->name }}</td>
                                <td class="border border-slate-300 py-1.5 px-2 text-center font-black bg-slate-50">{{ $tScores[$qc->id] ?? 90 }}</td>
                                <td class="border border-slate-300 py-1.5 px-3 text-slate-600">{{ $qc->description }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-teal-50 font-extrabold">
                            <td colspan="2" class="border border-slate-300 py-1.5 px-3 text-right uppercase text-teal-950">Nilai Akhir & Predikat Tahsin Wafa:</td>
                            <td class="border border-slate-300 py-1.5 px-2 text-center font-black text-teal-900">{{ $quranGrade->tahsin_final_score ?? 91 }}</td>
                            <td class="border border-slate-300 py-1.5 px-3 text-teal-900 font-black">{{ $quranGrade->tahsin_predicate ?? 'Mumtaz (Sangat Baik)' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tahfidz & Tasmi' -->
            <div class="mb-4">
                <h4 class="font-extrabold uppercase text-[11px] text-teal-950 mb-2">B. Tahfidz Al-Qur'an & Ujian Tasmi'</h4>
                <div class="grid grid-cols-2 gap-3 text-[10px]">
                    <table class="w-full border-collapse border border-slate-300">
                        <tr><td class="border border-slate-300 py-1 px-2 font-semibold w-32 bg-slate-50">Target Hafalan SIT</td><td class="border border-slate-300 py-1 px-2 font-bold">{{ $quranGrade->tahfidz_target ?? 'Juz 30' }}</td></tr>
                        <tr><td class="border border-slate-300 py-1 px-2 font-semibold bg-slate-50">Capaian Ziyadah</td><td class="border border-slate-300 py-1 px-2 font-extrabold text-emerald-900">{{ $quranGrade->tahfidz_achievement ?? 'Tuntas Juz 30' }}</td></tr>
                        <tr><td class="border border-slate-300 py-1 px-2 font-semibold bg-slate-50">Nilai & Predikat</td><td class="border border-slate-300 py-1 px-2 font-bold">{{ $quranGrade->tahfidz_score ?? 92 }} ({{ $quranGrade->tahfidz_predicate ?? 'Mutqin' }})</td></tr>
                    </table>

                    <div class="p-2.5 bg-emerald-50 rounded-xl border border-emerald-200">
                        <span class="text-[9px] font-extrabold uppercase text-emerald-800">Hasil Ujian Tasmi' Sekali Duduk:</span>
                        <p class="font-black text-emerald-950 text-xs mt-1">🏆 {{ $quranGrade->tasmi_exam_result ?? 'Lulus Tasmi\' 1/2 Juz Sekali Duduk' }}</p>
                        <p class="text-[9px] text-emerald-700 italic mt-1">* Disimak langsung oleh Ustadz Penguji Al-Qur'an.</p>
                    </div>
                </div>
            </div>

            <!-- Catatan Guru Quran -->
            <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-300 text-[10px] mb-4">
                <strong class="text-teal-950 uppercase block mb-0.5">Catatan Pembimbing Al-Qur'an:</strong>
                <p class="italic text-slate-700">"{{ $quranGrade->tahsin_notes ?? 'Makhraj fasih, irama wafa terbentuk sangat merdu dan tartil. Pertahankan muraja\'ah rutin di rumah.' }}"</p>
            </div>

            <!-- Tanda Tangan Lembar Quran -->
            <div class="grid grid-cols-3 gap-2 text-center text-[10px] pt-3 border-t border-slate-200">
                <div>
                    <p class="text-slate-500">Orang Tua / Wali</p>
                    <div class="h-14"></div>
                    <p class="font-bold border-b border-slate-400 inline-block px-4">................................</p>
                </div>
                <div>
                    <p class="text-slate-500">Koordinator Al-Qur'an / Wafa</p>
                    <div class="h-14 flex items-center justify-center">
                        @if($unit->quran_coordinator_signature_path && ($unit->print_settings['show_signature'] ?? true))
                            <img src="{{ asset($unit->quran_coordinator_signature_path) }}" class="h-11 w-auto object-contain">
                        @endif
                    </div>
                    <p class="font-bold border-b border-slate-900 inline-block px-2">{{ $unit->quran_coordinator_name ?? 'Koordinator Qur\'an' }}</p>
                </div>
                <div>
                    <p class="text-slate-500">{{ $unit->report_city }}, {{ optional($unit->report_date)->translatedFormat('d F Y') ?? date('d F Y') }}</p>
                    <div class="h-14 flex items-center justify-center relative">
                        @if($unit->principal_signature_path && ($unit->print_settings['show_signature'] ?? true))
                            <img src="{{ asset($unit->principal_signature_path) }}" class="h-12 w-auto object-contain z-10">
                        @endif
                        @if($unit->stamp_path && ($unit->print_settings['show_stamp'] ?? true))
                            <img src="{{ asset($unit->stamp_path) }}" class="h-12 w-auto object-contain absolute opacity-80 -rotate-6 z-20 pointer-events-none">
                        @endif
                    </div>
                    <p class="font-bold border-b border-slate-900 inline-block px-2">{{ $unit->principal_name ?? 'Kepala Sekolah' }}</p>
                </div>
            </div>
        </div>


        <!-- ========================================================================= -->
        <!-- HALAMAN 4: RAPOR KARAKTER & BPI (7 SKL JSIT) -->
        <!-- ========================================================================= -->
        <div class="page-sheet bg-white p-8 rounded-3xl border border-slate-200 shadow-sm text-slate-900 text-xs">
            <div class="text-center mb-3">
                <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-800 font-extrabold text-[10px] border border-indigo-200 uppercase">
                    JARINGAN SEKOLAH ISLAM TERPADU (JSIT) INDONESIA
                </span>
                <h3 class="text-sm font-black uppercase tracking-wider text-slate-900 mt-2">BAGIAN III: RAPOR MUTU KARAKTER & BINA PRIBADI ISLAMI (BPI)</h3>
                <p class="text-[10px] text-slate-500">{{ $unit->name }} • {{ $cs->student->full_name }} ({{ $cs->classroom->name }})</p>
            </div>

            <!-- Tabel 7 SKL JSIT -->
            <div class="mb-4">
                <h4 class="font-extrabold uppercase text-[11px] text-indigo-950 mb-1.5">A. Pencapaian 7 Standar Kompetensi Lulusan (SKL) JSIT</h4>
                <table class="w-full border-collapse border border-slate-300 text-[10px] mb-2">
                    <thead>
                        <tr class="bg-indigo-50 text-indigo-950 font-extrabold text-center uppercase">
                            <th class="border border-slate-300 py-1.5 px-2 w-8">No</th>
                            <th class="border border-slate-300 py-1.5 px-2.5 w-48 text-left">Standar Karakter JSIT</th>
                            <th class="border border-slate-300 py-1.5 px-2 w-16">Predikat</th>
                            <th class="border border-slate-300 py-1.5 px-2.5 text-left">Indikator Pembiasaan Adab & Karakter</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $cScores = $characterGrade->indicator_scores ?? [];
                            $noC = 1;
                        @endphp
                        @foreach($characterIndicators as $ci)
                            <tr>
                                <td class="border border-slate-300 py-1.5 px-2 text-center font-bold">{{ $noC++ }}</td>
                                <td class="border border-slate-300 py-1.5 px-2.5 font-bold text-slate-900">{{ $ci->standard_name }}</td>
                                <td class="border border-slate-300 py-1.5 px-2 text-center font-black text-indigo-900 bg-slate-50">
                                    {{ $cScores[$ci->id] ?? 'BSB' }}
                                </td>
                                <td class="border border-slate-300 py-1.5 px-2.5 text-slate-700 text-[9.5px]">
                                    {{ $ci->indicator_name }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mutabaah Yaumiyah -->
            <div class="mb-4">
                <h4 class="font-extrabold uppercase text-[11px] text-indigo-950 mb-1.5">B. Rekapitulasi Mutabaah Ibadah Yaumiyah</h4>
                <div class="grid grid-cols-4 gap-2 text-center text-[10px]">
                    <div class="p-2 bg-slate-50 border border-slate-300 rounded-lg">
                        <span class="text-slate-500 block text-[9px]">Sholat 5 Waktu</span>
                        <strong class="text-indigo-950 text-[11px]">{{ $characterGrade->mutabaah_sholat_fardhu ?? 'Selalu Berjamaah' }}</strong>
                    </div>
                    <div class="p-2 bg-slate-50 border border-slate-300 rounded-lg">
                        <span class="text-slate-500 block text-[9px]">Sholat Dhuha</span>
                        <strong class="text-indigo-950 text-[11px]">{{ $characterGrade->mutabaah_sholat_dhuha ?? 'Rutin' }}</strong>
                    </div>
                    <div class="p-2 bg-slate-50 border border-slate-300 rounded-lg">
                        <span class="text-slate-500 block text-[9px]">Tilawah Qur'an</span>
                        <strong class="text-indigo-950 text-[11px]">{{ $characterGrade->mutabaah_tilawah ?? '1/2 Juz per Hari' }}</strong>
                    </div>
                    <div class="p-2 bg-slate-50 border border-slate-300 rounded-lg">
                        <span class="text-slate-500 block text-[9px]">Infaq Jum'at</span>
                        <strong class="text-indigo-950 text-[11px]">{{ $characterGrade->mutabaah_infaq ?? 'Rutin Tiap Pekan' }}</strong>
                    </div>
                </div>
            </div>

            <!-- Catatan Pembina BPI -->
            <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-300 text-[10px] mb-4">
                <strong class="text-indigo-950 uppercase block mb-0.5">Catatan Pembina BPI / Guru Karakter:</strong>
                <p class="italic text-slate-700">"{{ $characterGrade->bpi_mentor_notes ?? 'Ananda menunjukkan kesantunan dan adab yang sangat baik dalam keseharian di sekolah. Semoga menjadi anak sholeh yang senantiasa membanggakan orang tua.' }}"</p>
            </div>

            <!-- Tanda Tangan Lembar Karakter & Pengesahan Akhir -->
            <div class="grid grid-cols-3 gap-2 text-center text-[10px] pt-3 border-t border-slate-200">
                <div>
                    <p class="text-slate-500">Orang Tua / Wali Santri</p>
                    <div class="h-14"></div>
                    <p class="font-bold border-b border-slate-400 inline-block px-4">................................</p>
                </div>
                <div>
                    <p class="text-slate-500">Pembina BPI / Wali Kelas</p>
                    <div class="h-14 flex items-center justify-center">
                        @if(!empty($cs->classroom->homeroomTeacher->signature_path) && ($unit->print_settings['show_signature'] ?? true))
                            <img src="{{ asset($cs->classroom->homeroomTeacher->signature_path) }}" class="h-11 w-auto object-contain">
                        @endif
                    </div>
                    <p class="font-bold border-b border-slate-900 inline-block px-2">{{ $cs->classroom->homeroomTeacher->name ?? 'Wali Kelas' }}</p>
                </div>
                <div>
                    <p class="text-slate-500">{{ $unit->report_city }}, {{ optional($unit->report_date)->translatedFormat('d F Y') ?? date('d F Y') }}</p>
                    <div class="h-14 flex items-center justify-center relative">
                        @if($unit->principal_signature_path && ($unit->print_settings['show_signature'] ?? true))
                            <img src="{{ asset($unit->principal_signature_path) }}" class="h-12 w-auto object-contain z-10">
                        @endif
                        @if($unit->stamp_path && ($unit->print_settings['show_stamp'] ?? true))
                            <img src="{{ asset($unit->stamp_path) }}" class="h-12 w-auto object-contain absolute opacity-80 -rotate-6 z-20 pointer-events-none">
                        @endif
                    </div>
                    <p class="font-bold border-b border-slate-900 inline-block px-2">{{ $unit->principal_name ?? 'Kepala Sekolah' }}</p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
