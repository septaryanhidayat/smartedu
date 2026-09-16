<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rapor Al-Qur'an Metode Wafa - {{ $cs->student->full_name }}</title>
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
        }
    </style>
</head>
<body class="p-6 md:p-10">

    <!-- Print Action Bar -->
    <div class="no-print max-w-4xl mx-auto mb-6 p-4 bg-slate-900 text-white rounded-2xl flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-3">
            <span class="text-xl">📖</span>
            <div>
                <h3 class="font-bold text-sm text-white">Pratinjau Rapor Al-Qur'an (Metode Wafa & Tahfidz)</h3>
                <p class="text-xs text-teal-400">{{ $cs->student->full_name }} ({{ $cs->classroom->name }})</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-600 text-white font-extrabold text-xs shadow transition flex items-center gap-1.5">
                <span>🖨️ Cetak Rapor Al-Qur'an</span>
            </button>
            <button onclick="window.close()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition">
                Tutup
            </button>
        </div>
    </div>

    <!-- Sheet Container -->
    <div class="print-container max-w-4xl mx-auto bg-white p-8 rounded-3xl border border-slate-200 shadow-sm text-slate-900 text-xs">
        
        <!-- Header Kop Surat -->
        @if($unit->letterhead_path && file_exists(public_path($unit->letterhead_path)))
            <div class="mb-4 text-center">
                <img src="{{ asset($unit->letterhead_path) }}" alt="Kop Surat" class="w-full max-h-28 object-contain mx-auto">
            </div>
        @else
            <div class="border-b-2 border-slate-900 pb-3 mb-4 flex items-center gap-4">
                <img src="{{ $unit->logo_url }}" alt="Logo" class="w-16 h-16 object-contain">
                <div class="flex-1 text-center pr-12">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest leading-none">LEMBAGA PENDIDIKAN AL-QUR'AN & KEKHASAN SIT</p>
                    <h1 class="text-lg font-black text-slate-900 uppercase tracking-tight mt-1">{{ $unit->name }}</h1>
                    <p class="text-[10px] text-slate-600 mt-0.5">{{ $unit->address }}</p>
                    <p class="text-[10px] text-teal-800 font-bold">Pusat Pembelajaran Al-Qur'an Metode Wafa (Belajar Al-Qur'an Metode Otak Kanan)</p>
                </div>
            </div>
        @endif

        <!-- Judul Dokumen -->
        <div class="text-center mb-4">
            <h2 class="text-sm font-black uppercase tracking-wider text-slate-900">LAPORAN CAPAIAN PEMBELAJARAN AL-QUR'AN</h2>
            <p class="text-[11px] font-bold text-teal-800">TAHSIN METODE WAFA & TAHFIDZ AL-QUR'AN</p>
        </div>

        <!-- Identitas Siswa -->
        <div class="grid grid-cols-2 gap-x-6 gap-y-1 mb-5 p-3 bg-teal-50/40 rounded-xl border border-teal-200/80 text-[11px]">
            <div class="flex"><span class="w-28 text-slate-500 font-semibold">Nama Santri</span><span class="font-bold text-slate-900">: {{ $cs->student->full_name }}</span></div>
            <div class="flex"><span class="w-28 text-slate-500 font-semibold">Kelas</span><span class="font-bold text-slate-900">: {{ $cs->classroom->name }}</span></div>
            <div class="flex"><span class="w-28 text-slate-500 font-semibold">NIS / NISN</span><span class="font-bold text-slate-900">: {{ $cs->student->nis ?? '-' }} / {{ $cs->student->nisn ?? '-' }}</span></div>
            <div class="flex"><span class="w-28 text-slate-500 font-semibold">Semester</span><span class="font-bold text-slate-900">: {{ ucfirst($cs->academicYear->semester) }}</span></div>
            <div class="flex"><span class="w-28 text-slate-500 font-semibold">Metode Tahsin</span><span class="font-bold text-teal-800">: Wafa (Otak Kanan)</span></div>
            <div class="flex"><span class="w-28 text-slate-500 font-semibold">Tahun Pelajaran</span><span class="font-bold text-slate-900">: {{ $cs->academicYear->name }}</span></div>
        </div>

        <!-- Bagian 1: TAHSIN METODE WAFA -->
        <div class="mb-5">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-extrabold uppercase text-xs text-teal-900 flex items-center gap-1.5">
                    <span>A.</span> <span>Capaian Tahsin Metode Wafa</span>
                </h3>
                <span class="text-[11px] font-bold text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-lg border border-slate-200">
                    Tingkat/Buku: <strong class="text-teal-800">{{ $quranGrade->tahsin_level ?? 'Buku Wafa 3' }}</strong>
                </span>
            </div>

            <table class="w-full border-collapse border border-slate-300 text-[11px] mb-3">
                <thead>
                    <tr class="bg-teal-50/80 text-teal-950 font-extrabold text-center uppercase">
                        <th class="border border-slate-300 py-2 px-2 w-8">No</th>
                        <th class="border border-slate-300 py-2 px-3 text-left">Komponen Penilaian (Rubrik Wafa)</th>
                        <th class="border border-slate-300 py-2 px-2 w-20">Nilai (0-100)</th>
                        <th class="border border-slate-300 py-2 px-3 text-left">Deskripsi Standar Mutu Wafa</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $tScores = $quranGrade->tahsin_scores ?? [];
                        $no = 1; 
                    @endphp
                    @forelse($criteria as $c)
                        <tr>
                            <td class="border border-slate-300 py-2 px-2 text-center font-bold">{{ $no++ }}</td>
                            <td class="border border-slate-300 py-2 px-3 font-bold text-slate-900">
                                {{ $c->name }}
                            </td>
                            <td class="border border-slate-300 py-2 px-2 text-center font-black text-slate-900 bg-slate-50/50">
                                {{ $tScores[$c->id] ?? 90 }}
                            </td>
                            <td class="border border-slate-300 py-2 px-3 text-slate-700">
                                {{ $c->description }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="border border-slate-300 py-3 text-center text-slate-400">Belum ada rubrik kriteria.</td>
                        </tr>
                    @endforelse
                    <tr class="bg-teal-50/40 font-extrabold">
                        <td colspan="2" class="border border-slate-300 py-2 px-3 text-right uppercase text-teal-950">Nilai Akhir & Predikat Tahsin Wafa :</td>
                        <td class="border border-slate-300 py-2 px-2 text-center text-sm text-teal-900 font-black">
                            {{ $quranGrade->tahsin_final_score ?? 91 }}
                        </td>
                        <td class="border border-slate-300 py-2 px-3 text-teal-900">
                            <strong>{{ $quranGrade->tahsin_predicate ?? 'Mumtaz (Sangat Baik)' }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Bagian 2: TAHFIDZ AL-QUR'AN & TASMI' -->
        <div class="mb-5">
            <h3 class="font-extrabold uppercase text-xs text-teal-900 mb-2 flex items-center gap-1.5">
                <span>B.</span> <span>Capaian Tahfidz Al-Qur'an & Ujian Tasmi'</span>
            </h3>

            <div class="grid grid-cols-2 gap-4">
                <table class="w-full border-collapse border border-slate-300 text-[11px]">
                    <tr class="bg-slate-50">
                        <td class="border border-slate-300 py-2 px-3 font-bold w-40">Target Kurikulum SIT</td>
                        <td class="border border-slate-300 py-2 px-3 text-slate-800 font-semibold">
                            {{ $quranGrade->tahfidz_target ?? 'Juz 30 (An-Naba s/d An-Nas)' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 py-2 px-3 font-bold">Capaian Ziyadah Santri</td>
                        <td class="border border-slate-300 py-2 px-3 text-emerald-900 font-extrabold">
                            {{ $quranGrade->tahfidz_achievement ?? 'Tuntas Juz 30' }}
                        </td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="border border-slate-300 py-2 px-3 font-bold">Nilai & Predikat Mutqin</td>
                        <td class="border border-slate-300 py-2 px-3">
                            <span class="font-black text-slate-900">{{ $quranGrade->tahfidz_score ?? 92 }}</span>
                            <span class="ml-2 font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px]">
                                {{ $quranGrade->tahfidz_predicate ?? 'Mutqin' }}
                            </span>
                        </td>
                    </tr>
                </table>

                <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-200 flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-emerald-800 tracking-wider">Hasil Ujian Tasmi' Sekali Duduk:</span>
                        <p class="font-black text-emerald-950 text-xs mt-1">
                            🏆 {{ $quranGrade->tasmi_exam_result ?? 'Lulus Tasmi\' 1/2 Juz Sekali Duduk dengan Predikat Mumtaz' }}
                        </p>
                    </div>
                    <p class="text-[10px] text-emerald-700 mt-2 italic">
                        * Ujian tasmi' disimak langsung oleh Majelis Penguji Al-Qur'an SIT.
                    </p>
                </div>
            </div>
        </div>

        <!-- Bagian 3: Catatan Pembimbing Al-Qur'an -->
        <div class="mb-6 p-3 bg-slate-50 rounded-xl border border-slate-300 text-[11px]">
            <span class="font-extrabold uppercase text-[10px] text-teal-900 block mb-1">Catatan Pembimbing Al-Qur'an:</span>
            <p class="text-slate-800 italic leading-relaxed">
                "{{ $quranGrade->tahsin_notes ?? 'Alhamdulillah makhraj dan tajwid sangat baik. Irama nada lagu Wafa terbentuk indah. Pertahankan istiqomah tilawah dan muraja\'ah hafalan di rumah.' }}"
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

            <!-- Koordinator Al-Qur'an -->
            <div>
                <p class="text-slate-600">&nbsp;</p>
                <p class="font-semibold text-slate-800">Koordinator Al-Qur'an / Wafa</p>
                <div class="h-20 flex items-center justify-center">
                    @if($unit->quran_coordinator_signature_path && ($unit->print_settings['show_signature'] ?? true))
                        <img src="{{ asset($unit->quran_coordinator_signature_path) }}" class="h-14 w-auto object-contain">
                    @endif
                </div>
                <p class="font-bold text-slate-900 border-b border-slate-900 inline-block px-2 pb-0.5">
                    {{ $unit->quran_coordinator_name ?? 'Ustadz Koordinator Qur\'an' }}
                </p>
                <p class="text-[10px] text-slate-500">NIP: {{ $unit->quran_coordinator_nip ?? '-' }}</p>
            </div>

            <!-- Kepala Sekolah -->
            <div class="relative">
                <p class="text-slate-600">{{ $unit->report_city }}, {{ optional($unit->report_date)->translatedFormat('d F Y') ?? date('d F Y') }}</p>
                <p class="font-semibold text-slate-800">Kepala Sekolah</p>
                <div class="h-20 flex items-center justify-center relative">
                    @if($unit->principal_signature_path && ($unit->print_settings['show_signature'] ?? true))
                        <img src="{{ asset($unit->principal_signature_path) }}" class="h-16 w-auto object-contain z-10">
                    @endif
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
