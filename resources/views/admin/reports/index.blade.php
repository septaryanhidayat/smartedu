@extends('admin.layout')

@section('title', 'Pusat Cetak E-Rapor - ' . $activeUnit->name)

@section('content')
<div class="space-y-8 max-w-7xl">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 border border-teal-200 text-teal-800 text-xs font-bold mb-2">
                <span>🖨️ Cetak E-Rapor Terpadu</span>
                <span>•</span>
                <span class="uppercase tracking-wider">{{ $activeUnit->name }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pusat Cetak Dokumen E-Rapor SIT</h1>
            <p class="text-xs text-slate-500 mt-1">Pilih format cetak: <strong>Cetak Terpisah</strong> (Akademik, Al-Qur'an Wafa, Karakter BPI) atau <strong>Cetak Gabungan All-in-One</strong>.</p>
        </div>

        @if($selectedClassroom)
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.reports.print.leger', $selectedClassroom->id) }}" target="_blank"
                    class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2">
                    <span>📊</span> <span>Cetak Leger Kelas (1 Lembar)</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Classroom Filter -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
            <div class="flex-1">
                <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Rombongan Belajar (Kelas)</label>
                <select name="classroom_id" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-bold focus:ring-2 focus:ring-teal-500 focus:outline-none">
                    @foreach($classrooms as $c)
                        <option value="{{ $c->id }}" {{ ($selectedClassroom && $selectedClassroom->id == $c->id) ? 'selected' : '' }}>
                            {{ $c->name }} (Fase {{ $c->phase }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="py-2.5 px-6 rounded-xl bg-slate-900 text-white hover:bg-slate-800 text-xs font-bold transition flex items-center gap-2">
                    <span>🔍</span> <span>Tampilkan Santri</span>
                </button>
            </div>
        </form>
    </div>

    @if($selectedClassroom)
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Daftar Santri Siap Cetak: {{ $selectedClassroom->name }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Tahun Ajaran {{ $selectedClassroom->academicYear->name }} • Semester {{ ucfirst($selectedClassroom->academicYear->semester) }}</p>
                </div>

                <div class="flex items-center gap-3 text-xs">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-800 rounded-lg font-bold border border-emerald-200">
                        <span>✓</span> <span>Legalitas & TTD Siap</span>
                    </span>
                    <a href="{{ route('admin.report-settings.index') }}" class="text-teal-600 hover:text-teal-800 font-bold underline">
                        Edit Kop & TTD
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 font-extrabold uppercase border-b border-slate-200 text-[10px] tracking-wider">
                        <tr>
                            <th class="py-4 px-6 w-12 text-center">No</th>
                            <th class="py-4 px-6">Identitas Santri</th>
                            <th class="py-4 px-6 text-center">Status Kelengkapan Nilai</th>
                            <th class="py-4 px-6 text-center">Pilihan Cetak Terpisah</th>
                            <th class="py-4 px-6 text-center">Pilihan Cetak Gabungan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($selectedClassroom->classroomStudents as $idx => $cs)
                            @php
                                $hasAcademic = $cs->academicGrades->count() > 0;
                                $hasQuran = $cs->quranGrade !== null;
                                $hasChar = $cs->characterGrade !== null;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-6 text-center font-bold text-slate-900">{{ $idx + 1 }}</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $cs->student->photo_url }}" alt="" class="w-10 h-10 rounded-xl object-cover shadow-sm">
                                        <div>
                                            <p class="font-extrabold text-slate-900 text-sm">{{ $cs->student->full_name }}</p>
                                            <p class="text-[11px] text-slate-400">NIS: {{ $cs->student->nis ?? '-' }} • NISN: {{ $cs->student->nisn ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-6 text-center">
                                    <div class="inline-flex items-center gap-1.5 flex-wrap justify-center text-[10px] font-bold">
                                        <span class="px-2 py-0.5 rounded {{ $hasAcademic ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-400' }}">
                                            Akademik: {{ $cs->academicGrades->count() }} Mapel
                                        </span>
                                        <span class="px-2 py-0.5 rounded {{ $hasQuran ? 'bg-teal-100 text-teal-800' : 'bg-slate-100 text-slate-400' }}">
                                            Al-Qur'an: {{ $hasQuran ? 'Siap' : 'Kosong' }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded {{ $hasChar ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-400' }}">
                                            Karakter: {{ $hasChar ? 'Siap' : 'Kosong' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Cetak Terpisah Buttons -->
                                <td class="py-4 px-6 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('admin.reports.print.academic', $cs->id) }}" target="_blank"
                                            class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-[11px] transition shadow-xs flex items-center gap-1"
                                            title="Cetak Buku 1: Rapor Akademik Kemendikbud">
                                            <span>📄</span> <span>Akademik</span>
                                        </a>

                                        <a href="{{ route('admin.reports.print.quran', $cs->id) }}" target="_blank"
                                            class="px-2.5 py-1.5 rounded-lg bg-teal-50 hover:bg-teal-100 text-teal-800 font-bold text-[11px] transition border border-teal-200 shadow-xs flex items-center gap-1"
                                            title="Cetak Buku 2: Rapor Al-Qur'an Metode Wafa">
                                            <span>📖</span> <span>Al-Qur'an</span>
                                        </a>

                                        <a href="{{ route('admin.reports.print.character', $cs->id) }}" target="_blank"
                                            class="px-2.5 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-800 font-bold text-[11px] transition border border-indigo-200 shadow-xs flex items-center gap-1"
                                            title="Cetak Buku 3: Rapor Karakter & BPI 7 SKL JSIT">
                                            <span>🌙</span> <span>Karakter</span>
                                        </a>
                                    </div>
                                </td>

                                <!-- Cetak Gabungan Button -->
                                <td class="py-4 px-6 text-center">
                                    <a href="{{ route('admin.reports.print.bundle', $cs->id) }}" target="_blank"
                                        class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-extrabold text-xs shadow-md hover:shadow-lg transition transform active:scale-95 inline-flex items-center gap-2">
                                        <span>📑</span>
                                        <span>Cetak Gabungan (All-in-One)</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
