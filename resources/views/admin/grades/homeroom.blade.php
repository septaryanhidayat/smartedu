@extends('admin.layout')

@section('title', 'Catatan Wali Kelas, Absensi & Fisik - ' . $activeUnit->name)

@section('content')
<div class="space-y-8 max-w-7xl">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 border border-teal-200 text-teal-800 text-xs font-bold mb-2">
                <span>📋 Catatan Wali Kelas</span>
                <span>•</span>
                <span class="uppercase tracking-wider">{{ $activeUnit->name }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Absensi, Data Fisik, Ekstrakurikuler & Catatan Wali Kelas</h1>
            <p class="text-xs text-slate-500 mt-1">Input rekap kehadiran satu semester, tinggi/berat badan, kesehatan pendengaran/penglihatan/gigi, serta apresiasi wali kelas.</p>
        </div>
    </div>

    <!-- Classroom Filter -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.grades.homeroom') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
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
                    <span>🔍</span> <span>Tampilkan Siswa</span>
                </button>
            </div>
        </form>
    </div>

    @if($selectedClassroom)
        <form action="{{ route('admin.grades.homeroom.save') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="classroom_id" value="{{ $selectedClassroom->id }}">

            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Kelola Catatan & Kehadiran: {{ $selectedClassroom->name }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Wali Kelas: {{ $selectedClassroom->homeroomTeacher->name ?? 'Administrator' }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-700 font-extrabold uppercase border-b border-slate-200 text-[10px] tracking-wider">
                            <tr>
                                <th class="py-4 px-4 w-12 text-center" rowspan="2">No</th>
                                <th class="py-4 px-4 w-44" rowspan="2">Nama Santri</th>
                                <th class="py-3 px-3 text-center bg-slate-100 border-r border-slate-200" colspan="3">
                                    REKAP PRESENSI (HARI)
                                </th>
                                <th class="py-3 px-3 text-center bg-teal-50/50 border-r border-slate-200" colspan="3">
                                    PERKEMBANGAN FISIK & KESEHATAN
                                </th>
                                <th class="py-3 px-3 text-center bg-emerald-50/50" colspan="2">
                                    EKSTRAKURIKULER & CATATAN
                                </th>
                            </tr>
                            <tr class="border-b border-slate-200 bg-slate-50 text-[10px]">
                                <th class="py-2 px-2 text-center w-14">Sakit</th>
                                <th class="py-2 px-2 text-center w-14">Izin</th>
                                <th class="py-2 px-2 text-center w-14 border-r border-slate-200">Alpa</th>
                                <th class="py-2 px-2 text-center w-20">Tinggi (cm)</th>
                                <th class="py-2 px-2 text-center w-20">Berat (kg)</th>
                                <th class="py-2 px-2 text-center border-r border-slate-200">Kondisi Gigi</th>
                                <th class="py-2 px-3 w-48">Ekstrakurikuler</th>
                                <th class="py-2 px-4">Catatan Wali Kelas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @foreach($selectedClassroom->classroomStudents as $idx => $cs)
                                @php
                                    $ekstra = $cs->extracurricularGrades->first();
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition align-top">
                                    <td class="py-4 px-4 text-center font-bold text-slate-900">{{ $idx + 1 }}</td>
                                    <td class="py-4 px-4">
                                        <p class="font-extrabold text-slate-900 text-xs">{{ $cs->student->full_name }}</p>
                                        <p class="text-[10px] text-slate-400">NIS: {{ $cs->student->nis ?? '-' }}</p>
                                    </td>

                                    <!-- Absensi -->
                                    <td class="py-3 px-2 text-center">
                                        <input type="number" min="0" name="students[{{ $cs->id }}][attendance_sakit]" value="{{ $cs->attendance_sakit }}"
                                            class="w-12 text-center p-1 bg-slate-50 border border-slate-300 rounded text-xs font-bold">
                                    </td>
                                    <td class="py-3 px-2 text-center">
                                        <input type="number" min="0" name="students[{{ $cs->id }}][attendance_izin]" value="{{ $cs->attendance_izin }}"
                                            class="w-12 text-center p-1 bg-slate-50 border border-slate-300 rounded text-xs font-bold">
                                    </td>
                                    <td class="py-3 px-2 text-center border-r border-slate-200">
                                        <input type="number" min="0" name="students[{{ $cs->id }}][attendance_alpa]" value="{{ $cs->attendance_alpa }}"
                                            class="w-12 text-center p-1 bg-slate-50 border border-slate-300 rounded text-xs font-bold">
                                    </td>

                                    <!-- Fisik & Kesehatan -->
                                    <td class="py-3 px-2 text-center">
                                        <input type="number" min="0" name="students[{{ $cs->id }}][physical_height]" value="{{ $cs->physical_height ?? 135 }}"
                                            class="w-16 text-center p-1 bg-slate-50 border border-slate-300 rounded text-xs font-bold">
                                    </td>
                                    <td class="py-3 px-2 text-center">
                                        <input type="number" min="0" name="students[{{ $cs->id }}][physical_weight]" value="{{ $cs->physical_weight ?? 30 }}"
                                            class="w-16 text-center p-1 bg-slate-50 border border-slate-300 rounded text-xs font-bold">
                                    </td>
                                    <td class="py-3 px-2 text-center border-r border-slate-200">
                                        <input type="text" name="students[{{ $cs->id }}][physical_dental]" value="{{ $cs->physical_dental ?? 'Bersih & Terawat' }}"
                                            class="w-24 text-center p-1 bg-slate-50 border border-slate-300 rounded text-[11px]">
                                    </td>

                                    <!-- Ekstrakurikuler -->
                                    <td class="py-3 px-3 space-y-1">
                                        <input type="text" name="students[{{ $cs->id }}][ekstra_name]" value="{{ $ekstra->activity_name ?? 'Pramuka SIT' }}"
                                            class="w-full p-1 bg-slate-50 border border-slate-300 rounded text-[11px] font-bold" placeholder="Nama Kegiatan">
                                        <select name="students[{{ $cs->id }}][ekstra_pred]" class="w-full p-1 bg-slate-50 border border-slate-300 rounded text-[11px]">
                                            <option value="Sangat Baik" {{ ($ekstra->predicate ?? 'Sangat Baik') == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                                            <option value="Baik" {{ ($ekstra->predicate ?? '') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                            <option value="Cukup" {{ ($ekstra->predicate ?? '') == 'Cukup' ? 'selected' : '' }}>Cukup</option>
                                        </select>
                                    </td>

                                    <!-- Catatan Wali Kelas -->
                                    <td class="py-3 px-4">
                                        <textarea name="students[{{ $cs->id }}][homeroom_notes]" rows="2"
                                            class="w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-[11px] leading-tight focus:ring-1 focus:ring-teal-500 focus:outline-none"
                                            placeholder="Catatan perkembangan karakter & motivasi wali kelas...">{{ $cs->homeroom_notes ?? 'Ananda menunjukkan keteladanan yang sangat baik dalam sholat berjamaah dan ramah terhadap sesama teman. Tingkatkan terus belajarnya!' }}</textarea>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-xs text-slate-500">
                        👨‍🏫 Data presensi dan kesehatan fisik otomatis tampil di lembar laporan hasil belajar siswa.
                    </p>
                    <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-extrabold text-xs shadow-md hover:from-teal-500 hover:to-emerald-500 transition">
                        Simpan Catatan Wali Kelas & Presensi
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
