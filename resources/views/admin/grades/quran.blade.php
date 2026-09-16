@extends('admin.layout')

@section('title', 'Input Nilai Al-Qur\'an Metode Wafa - ' . $activeUnit->name)

@section('content')
<div class="space-y-8 max-w-7xl">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 border border-teal-200 text-teal-800 text-xs font-bold mb-2">
                <span>📖 Rapor Qur'ani SIT</span>
                <span>•</span>
                <span>Metode Wafa (Otak Kanan)</span>
                <span>•</span>
                <span class="uppercase tracking-wider">{{ $activeUnit->name }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Penilaian Al-Qur'an: Tahsin Wafa & Tahfidz</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola capaian jilid Wafa, rubrik penilaian dinamis (makhraj, tajwid, nada hijaz wafa), capaian ziyadah & kelulusan tasmi'.</p>
        </div>
    </div>

    <!-- Classroom Filter -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.grades.quran') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
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
        <form action="{{ route('admin.grades.quran.save') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="classroom_id" value="{{ $selectedClassroom->id }}">

            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Lembar Penilaian Al-Qur'an: {{ $selectedClassroom->name }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Penilaian Tahsin berbasis Metode Wafa terpadu dengan target Tahfidz SIT.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold px-3 py-1 rounded-xl bg-teal-50 text-teal-800 border border-teal-200">
                            Metode: Wafa Belajar Al-Qur'an
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-700 font-extrabold uppercase border-b border-slate-200 text-[10px] tracking-wider">
                            <tr>
                                <th class="py-4 px-4 w-12 text-center" rowspan="2">No</th>
                                <th class="py-4 px-4 w-48" rowspan="2">Nama Santri</th>
                                <th class="py-3 px-4 text-center bg-teal-50/50 border-r border-slate-200" colspan="{{ 2 + $tahsinCriteria->count() }}">
                                    TAHSIN AL-QUR'AN (METODE WAFA)
                                </th>
                                <th class="py-3 px-4 text-center bg-emerald-50/50" colspan="4">
                                    TAHFIDZ AL-QUR'AN & TASMI'
                                </th>
                            </tr>
                            <tr class="border-b border-slate-200 bg-slate-50">
                                <th class="py-2 px-3">Capaian Jilid / Buku Wafa</th>
                                @foreach($tahsinCriteria as $tc)
                                    <th class="py-2 px-2 text-center" title="{{ $tc->name }}">
                                        {{ $tc->name }}
                                    </th>
                                @endforeach
                                <th class="py-2 px-3 text-center font-black text-teal-800 border-r border-slate-200">Nilai Tahsin</th>
                                <th class="py-2 px-3">Target & Capaian Hafalan</th>
                                <th class="py-2 px-2 text-center">Nilai</th>
                                <th class="py-2 px-3">Hasil Ujian Tasmi'</th>
                                <th class="py-2 px-3">Catatan Pembimbing</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @foreach($studentsData as $idx => $item)
                                @php
                                    $cs = $item['classroom_student'];
                                    $grade = $item['grade'];
                                    $tScores = $item['tahsin_scores'];
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition align-top">
                                    <td class="py-4 px-4 text-center font-bold text-slate-900">{{ $idx + 1 }}</td>
                                    <td class="py-4 px-4">
                                        <p class="font-extrabold text-slate-900 text-xs">{{ $cs->student->full_name }}</p>
                                        <p class="text-[10px] text-slate-400">NIS: {{ $cs->student->nis ?? '-' }}</p>
                                    </td>

                                    <!-- Tahsin: Jilid Wafa -->
                                    <td class="py-3 px-3">
                                        <input type="text" name="quran[{{ $cs->id }}][tahsin_level]"
                                            value="{{ $grade->tahsin_level ?? 'Buku Wafa 3 Hal. 25' }}"
                                            class="w-36 px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-semibold focus:ring-1 focus:ring-teal-500 focus:outline-none"
                                            placeholder="Contoh: Wafa 4 Hal 20">
                                        <input type="hidden" name="quran[{{ $cs->id }}][tahsin_method]" value="Wafa">
                                    </td>

                                    <!-- Tahsin: Dynamic Criteria Scores -->
                                    @foreach($tahsinCriteria as $tc)
                                        <td class="py-3 px-2 text-center">
                                            <input type="number" step="0.1" min="0" max="100"
                                                name="quran[{{ $cs->id }}][tahsin_scores][{{ $tc->id }}]"
                                                value="{{ $tScores[$tc->id] ?? 90 }}"
                                                class="w-14 px-2 py-1.5 text-center bg-slate-50 border border-slate-300 rounded-lg text-xs font-bold focus:ring-1 focus:ring-teal-500 focus:outline-none">
                                        </td>
                                    @endforeach

                                    <!-- Tahsin: Final Score -->
                                    <td class="py-3 px-3 text-center border-r border-slate-200">
                                        <input type="number" step="0.1" min="0" max="100"
                                            name="quran[{{ $cs->id }}][tahsin_final_score]"
                                            value="{{ $grade->tahsin_final_score ?? 91 }}"
                                            class="w-16 px-2 py-1.5 text-center bg-teal-50 text-teal-900 border border-teal-300 rounded-lg text-xs font-black focus:ring-1 focus:ring-teal-500 focus:outline-none">
                                        <p class="text-[10px] text-teal-700 font-bold mt-1">{{ $grade->tahsin_predicate ?? 'Mumtaz' }}</p>
                                    </td>

                                    <!-- Tahfidz: Target & Capaian -->
                                    <td class="py-3 px-3 space-y-1.5">
                                        <input type="text" name="quran[{{ $cs->id }}][tahfidz_target]"
                                            value="{{ $grade->tahfidz_target ?? 'Juz 30 (An-Naba s/d An-Nas)' }}"
                                            class="w-44 px-2 py-1 bg-slate-50 border border-slate-200 rounded text-[11px] font-medium"
                                            placeholder="Target: Juz 30">
                                        <input type="text" name="quran[{{ $cs->id }}][tahfidz_achievement]"
                                            value="{{ $grade->tahfidz_achievement ?? 'Tuntas Juz 30' }}"
                                            class="w-44 px-2 py-1 bg-emerald-50/50 border border-emerald-200 rounded text-[11px] font-bold text-emerald-900"
                                            placeholder="Capaian saat ini">
                                    </td>

                                    <!-- Tahfidz: Score -->
                                    <td class="py-3 px-2 text-center">
                                        <input type="number" step="0.1" min="0" max="100"
                                            name="quran[{{ $cs->id }}][tahfidz_score]"
                                            value="{{ $grade->tahfidz_score ?? 92 }}"
                                            class="w-16 px-2 py-1.5 text-center bg-emerald-50 text-emerald-900 border border-emerald-300 rounded-lg text-xs font-black focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                                        <span class="inline-block mt-1 text-[9px] px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold">
                                            {{ $grade->tahfidz_predicate ?? 'Mutqin' }}
                                        </span>
                                    </td>

                                    <!-- Tahfidz: Hasil Tasmi' -->
                                    <td class="py-3 px-3">
                                        <input type="text" name="quran[{{ $cs->id }}][tasmi_exam_result]"
                                            value="{{ $grade->tasmi_exam_result ?? 'Lulus Tasmi\' 1/2 Juz Sekali Duduk' }}"
                                            class="w-40 px-2 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium focus:ring-1 focus:ring-teal-500 focus:outline-none"
                                            placeholder="Hasil tasmi'">
                                    </td>

                                    <!-- Notes -->
                                    <td class="py-3 px-3">
                                        <textarea name="quran[{{ $cs->id }}][tahsin_notes]" rows="2"
                                            class="w-44 p-2 bg-slate-50 border border-slate-200 rounded-lg text-[11px] leading-tight focus:ring-1 focus:ring-teal-500 focus:outline-none"
                                            placeholder="Catatan motivasi penguji...">{{ $grade->tahsin_notes ?? 'Makhraj fasih dan nada wafa merdu.' }}</textarea>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-xs text-slate-500">
                        ⭐ <strong>Standar SIT:</strong> Rapor Al-Qur'an mencakup aspek kelancaran Wafa, makhraj, tajwid, irama nada hijaz, serta ketuntasan tasmi' hafalan Al-Qur'an.
                    </p>
                    <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-extrabold text-xs shadow-md hover:from-teal-500 hover:to-emerald-500 transition">
                        Simpan Nilai Al-Qur'an Wafa
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
