<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rapor Akademik - {{ $cs->student->full_name }}</title>
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
            size: A4;
            margin: 12mm 15mm;
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
            }
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body class="p-6 md:p-10">

    <!-- Print Action Bar (Hidden when printed) -->
    <div class="no-print max-w-4xl mx-auto mb-6 p-4 bg-slate-900 text-white rounded-2xl flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-3">
            <span class="text-xl">📄</span>
            <div>
                <h3 class="font-bold text-sm text-white">Pratinjau Rapor Akademik (Kurikulum Merdeka)</h3>
                <p class="text-xs text-slate-400">{{ $cs->student->full_name }} ({{ $cs->classroom->name }})</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-xs shadow transition flex items-center gap-1.5">
                <span>🖨️ Cetak Dokumen / Simpan PDF</span>
            </button>
            <button onclick="window.close()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition">
                Tutup
            </button>
        </div>
    </div>

    <!-- Paper Sheet Container -->
    <div class="print-container max-w-4xl mx-auto bg-white p-8 rounded-3xl border border-slate-200 shadow-sm text-slate-900 text-xs">
        
        <!-- Header / Kop Surat -->
        @if($unit->letterhead_path && file_exists(public_path($unit->letterhead_path)))
            <div class="mb-4 text-center">
                <img src="{{ asset($unit->letterhead_path) }}" alt="Kop Surat" class="w-full max-h-28 object-contain mx-auto">
            </div>
        @else
            <div class="border-b-2 border-slate-900 pb-3 mb-4 flex items-center gap-4">
                <img src="{{ $unit->logo_url }}" alt="Logo" class="w-16 h-16 object-contain">
                <div class="flex-1 text-center pr-12">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest leading-none">YAYASAN PENDIDIKAN ISLAM TERPADU</p>
                    <h1 class="text-lg font-black text-slate-900 uppercase tracking-tight mt-1">{{ $unit->name }}</h1>
                    <p class="text-[10px] text-slate-600 mt-0.5">NPSN: {{ $unit->npsn ?? '-' }} • {{ $unit->address }}</p>
                    <p class="text-[10px] text-slate-500">Telp: {{ $unit->phone ?? '-' }} | Email: {{ $unit->email ?? '-' }} | Website: {{ $unit->website ?? '-' }}</p>
                </div>
            </div>
        @endif

        <!-- Judul Rapor -->
        <div class="text-center mb-4">
            <h2 class="text-sm font-black uppercase tracking-wider text-slate-900">LAPORAN HASIL BELAJAR (RAPOR AKADEMIK)</h2>
            <p class="text-[11px] font-bold text-slate-600">KURIKULUM MERDEKA</p>
        </div>

        <!-- Identitas Siswa -->
        <div class="grid grid-cols-2 gap-x-6 gap-y-1 mb-4 p-3 bg-slate-50 rounded-xl border border-slate-200 text-[11px]">
            <div class="flex"><span class="w-28 text-slate-500 font-semibold">Nama Peserta Didik</span><span class="font-bold text-slate-900">: {{ $cs->student->full_name }}</span></div>
            <div class="flex"><span class="w-28 text-slate-500 font-semibold">Kelas / Fase</span><span class="font-bold text-slate-900">: {{ $cs->classroom->name }} / Fase {{ $cs->classroom->phase }}</span></div>
            <div class="flex"><span class="w-28 text-slate-500 font-semibold">NIS / NISN</span><span class="font-bold text-slate-900">: {{ $cs->student->nis ?? '-' }} / {{ $cs->student->nisn ?? '-' }}</span></div>
            <div class="flex"><span class="w-28 text-slate-500 font-semibold">Semester</span><span class="font-bold text-slate-900">: {{ ucfirst($cs->academicYear->semester) }}</span></div>
            <div class="flex"><span class="w-28 text-slate-500 font-semibold">Sekolah</span><span class="font-bold text-slate-900">: {{ $unit->name }}</span></div>
            <div class="flex"><span class="w-28 text-slate-500 font-semibold">Tahun Pelajaran</span><span class="font-bold text-slate-900">: {{ $cs->academicYear->name }}</span></div>
        </div>

        <!-- Tabel Nilai Akademik -->
        <div class="mb-5">
            <table class="w-full border-collapse border border-slate-300 text-[11px]">
                <thead>
                    <tr class="bg-slate-100 text-slate-800 font-extrabold text-center uppercase">
                        <th class="border border-slate-300 py-2 px-2 w-8">No</th>
                        <th class="border border-slate-300 py-2 px-3 w-48 text-left">Mata Pelajaran</th>
                        <th class="border border-slate-300 py-2 px-2 w-14">Nilai Akhir</th>
                        <th class="border border-slate-300 py-2 px-3">Capaian Kompetensi (Tujuan Pembelajaran)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse($academicGrades as $grade)
                        <tr>
                            <td class="border border-slate-300 py-2 px-2 text-center font-bold">{{ $no++ }}</td>
                            <td class="border border-slate-300 py-2 px-3 font-bold text-slate-900">
                                {{ $grade->subject->name }}
                                @if($grade->subject->category == 'diniyah_sit')
                                    <span class="block text-[9px] font-semibold text-emerald-700">* Kekhasan Diniyah SIT</span>
                                @endif
                            </td>
                            <td class="border border-slate-300 py-2 px-2 text-center font-black text-slate-900 bg-slate-50/50">
                                {{ $grade->final_grade ?? '-' }}
                            </td>
                            <td class="border border-slate-300 py-2 px-3 space-y-1">
                                @if($grade->highest_achievement)
                                    <p class="text-slate-800">
                                        <strong class="text-emerald-800">Capaian Tertinggi:</strong> {{ $grade->highest_achievement }}
                                    </p>
                                @endif
                                @if($grade->lowest_achievement)
                                    <p class="text-slate-700">
                                        <strong class="text-amber-800">Perlu Peningkatan:</strong> {{ $grade->lowest_achievement }}
                                    </p>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="border border-slate-300 py-4 text-center text-slate-400 italic">Belum ada data nilai akademik.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Ekstrakurikuler & Presensi (Grid 2 Kolom) -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <!-- Ekstrakurikuler -->
            <div>
                <h3 class="font-extrabold uppercase text-[10px] text-slate-700 mb-1">A. Kegiatan Ekstrakurikuler</h3>
                <table class="w-full border-collapse border border-slate-300 text-[10px]">
                    <thead>
                        <tr class="bg-slate-100 font-bold text-center">
                            <th class="border border-slate-300 py-1.5 px-2 text-left">Nama Kegiatan</th>
                            <th class="border border-slate-300 py-1.5 px-2 w-20">Predikat</th>
                            <th class="border border-slate-300 py-1.5 px-2 text-left">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cs->extracurricularGrades as $ekstra)
                            <tr>
                                <td class="border border-slate-300 py-1.5 px-2 font-bold">{{ $ekstra->activity_name }}</td>
                                <td class="border border-slate-300 py-1.5 px-2 text-center font-extrabold text-emerald-800">{{ $ekstra->predicate }}</td>
                                <td class="border border-slate-300 py-1.5 px-2 text-slate-600">{{ $ekstra->description ?? 'Aktif mengikuti kegiatan' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="border border-slate-300 py-2 text-center text-slate-400 italic">Tidak ada ekstrakurikuler.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Presensi & Fisik -->
            <div>
                <h3 class="font-extrabold uppercase text-[10px] text-slate-700 mb-1">B. Ketidakhadiran & Perkembangan Fisik</h3>
                <div class="grid grid-cols-2 gap-2 text-[10px]">
                    <table class="border-collapse border border-slate-300">
                        <tr class="bg-slate-50"><td class="border border-slate-300 py-1 px-2 font-semibold">Sakit</td><td class="border border-slate-300 py-1 px-2 text-center font-bold">{{ $cs->attendance_sakit }} hari</td></tr>
                        <tr><td class="border border-slate-300 py-1 px-2 font-semibold">Izin</td><td class="border border-slate-300 py-1 px-2 text-center font-bold">{{ $cs->attendance_izin }} hari</td></tr>
                        <tr class="bg-slate-50"><td class="border border-slate-300 py-1 px-2 font-semibold">Tanpa Keterangan</td><td class="border border-slate-300 py-1 px-2 text-center font-bold">{{ $cs->attendance_alpa }} hari</td></tr>
                    </table>

                    <table class="border-collapse border border-slate-300">
                        <tr class="bg-slate-50"><td class="border border-slate-300 py-1 px-2 font-semibold">Tinggi / Berat</td><td class="border border-slate-300 py-1 px-2 text-center font-bold">{{ $cs->physical_height ?? '-' }} cm / {{ $cs->physical_weight ?? '-' }} kg</td></tr>
                        <tr><td class="border border-slate-300 py-1 px-2 font-semibold">Pendengaran</td><td class="border border-slate-300 py-1 px-2 text-center font-bold">{{ $cs->physical_hearing ?? 'Baik' }}</td></tr>
                        <tr class="bg-slate-50"><td class="border border-slate-300 py-1 px-2 font-semibold">Kondisi Gigi</td><td class="border border-slate-300 py-1 px-2 text-center font-bold">{{ $cs->physical_dental ?? 'Bersih' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Catatan Wali Kelas -->
        <div class="mb-6 p-3 bg-slate-50 rounded-xl border border-slate-300 text-[11px]">
            <span class="font-extrabold uppercase text-[10px] text-slate-700 block mb-1">Catatan Wali Kelas:</span>
            <p class="text-slate-800 italic leading-relaxed">
                "{{ $cs->homeroom_notes ?? 'Tingkatkan terus prestasi belajarnya dan pertahankan akhlak mulia dalam keseharian.' }}"
            </p>
        </div>

        <!-- Tanda Tangan & Titimangsa Legalitas -->
        <div class="pt-4 border-t border-slate-200 grid grid-cols-3 gap-4 text-center text-[11px]">
            <!-- Orang Tua -->
            <div>
                <p class="text-slate-600">Mengetahui,</p>
                <p class="font-semibold text-slate-800">Orang Tua / Wali Santri</p>
                <div class="h-20"></div>
                <p class="font-bold border-b border-slate-400 inline-block px-8 pb-0.5">........................................</p>
            </div>

            <!-- Wali Kelas -->
            <div>
                <p class="text-slate-600">&nbsp;</p>
                <p class="font-semibold text-slate-800">Wali Kelas</p>
                <div class="h-20 flex items-center justify-center">
                    @if(!empty($cs->classroom->homeroomTeacher->signature_path) && ($unit->print_settings['show_signature'] ?? true))
                        <img src="{{ asset($cs->classroom->homeroomTeacher->signature_path) }}" class="h-14 w-auto object-contain">
                    @endif
                </div>
                <p class="font-bold text-slate-900 border-b border-slate-900 inline-block px-2 pb-0.5">
                    {{ $cs->classroom->homeroomTeacher->name ?? 'Ustadz Wali Kelas' }}
                </p>
                <p class="text-[10px] text-slate-500">NIP: {{ $cs->classroom->homeroomTeacher->nip ?? '-' }}</p>
            </div>

            <!-- Kepala Sekolah -->
            <div class="relative">
                <p class="text-slate-600">{{ $unit->report_city }}, {{ optional($unit->report_date)->translatedFormat('d F Y') ?? date('d F Y') }}</p>
                <p class="font-semibold text-slate-800">Kepala Sekolah</p>
                <div class="h-20 flex items-center justify-center relative">
                    <!-- Tanda Tangan Digital -->
                    @if($unit->principal_signature_path && ($unit->print_settings['show_signature'] ?? true))
                        <img src="{{ asset($unit->principal_signature_path) }}" class="h-16 w-auto object-contain z-10">
                    @endif
                    <!-- Stempel Resmi Sekolah -->
                    @if($unit->stamp_path && ($unit->print_settings['show_stamp'] ?? true))
                        <img src="{{ asset($unit->stamp_path) }}" class="h-16 w-auto object-contain absolute opacity-80 -rotate-6 z-20 pointer-events-none">
                    @endif
                </div>
                <p class="font-bold text-slate-900 border-b border-slate-900 inline-block px-2 pb-0.5">
                    {{ $unit->principal_name ?? 'Ustadz Kepala Sekolah' }}
                </p>
                <p class="text-[10px] text-slate-500">NIP: {{ $unit->principal_nip ?? '-' }}</p>
            </div>
        </div>

    </div>

</body>
</html>
