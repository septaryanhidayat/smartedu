@extends('admin.layout')

@section('title', 'Input Nilai Karakter 7 SKL JSIT & BPI - ' . $activeUnit->name)

@section('content')
<div class="space-y-8 max-w-7xl">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold mb-2">
                <span>🌙 Standar Mutu Karakter JSIT</span>
                <span>•</span>
                <span>Bina Pribadi Islami (BPI)</span>
                <span>•</span>
                <span class="uppercase tracking-wider">{{ $activeUnit->name }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Penilaian Karakter 7 SKL JSIT & Rekap Mutabaah Ibadah</h1>
            <p class="text-xs text-slate-500 mt-1">Evaluasi 7 Standar Kompetensi Lulusan JSIT (Akidah, Ibadah, Akhlak, Jasmani, Intelektual, Mandiri, Kontributif) dan kebiasaan ibadah yaumiyah.</p>
        </div>
    </div>

    <!-- Classroom Filter -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.grades.character') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
            <div class="flex-1">
                <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Rombongan Belajar (Kelas)</label>
                <select name="classroom_id" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-bold focus:ring-2 focus:ring-emerald-500 focus:outline-none">
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
        <form action="{{ route('admin.grades.character.save') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="classroom_id" value="{{ $selectedClassroom->id }}">

            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Lembar Karakter & BPI: {{ $selectedClassroom->name }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Predikat: <strong>BSB</strong> (Berkembang Sangat Baik), <strong>B</strong> (Berkembang Baik), <strong>MB</strong> (Mulai Berkembang), <strong>PB</strong> (Perlu Bimbingan)</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-700 font-extrabold uppercase border-b border-slate-200 text-[10px] tracking-wider">
                            <tr>
                                <th class="py-4 px-4 w-12 text-center" rowspan="2">No</th>
                                <th class="py-4 px-4 w-48" rowspan="2">Nama Santri</th>
                                <th class="py-3 px-4 text-center bg-emerald-50/50 border-r border-slate-200" colspan="{{ $indicators->count() }}">
                                    7 STANDAR KOMPETENSI LULUSAN (SKL) JSIT
                                </th>
                                <th class="py-3 px-4 text-center bg-teal-50/50" colspan="4">
                                    REKAP MUTABAAH YAUMIYAH & CATATAN BPI
                                </th>
                            </tr>
                            <tr class="border-b border-slate-200 bg-slate-50">
                                @foreach($indicators as $ind)
                                    <th class="py-2 px-2 text-center" title="{{ $ind->indicator_name }}">
                                        {{ $ind->standard_code }}
                                    </th>
                                @endforeach
                                <th class="py-2 px-3 border-l border-slate-200">Shalat Fardhu</th>
                                <th class="py-2 px-3">Shalat Dhuha</th>
                                <th class="py-2 px-3">Tilawah Qur'an</th>
                                <th class="py-2 px-4">Catatan Pembina BPI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @foreach($studentsData as $idx => $item)
                                @php
                                    $cs = $item['classroom_student'];
                                    $grade = $item['grade'];
                                    $iScores = $item['indicator_scores'];
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition align-top">
                                    <td class="py-4 px-4 text-center font-bold text-slate-900">{{ $idx + 1 }}</td>
                                    <td class="py-4 px-4">
                                        <p class="font-extrabold text-slate-900 text-xs">{{ $cs->student->full_name }}</p>
                                        <p class="text-[10px] text-slate-400">NIS: {{ $cs->student->nis ?? '-' }}</p>
                                    </td>

                                    <!-- 7 SKL Selectors -->
                                    @foreach($indicators as $ind)
                                        <td class="py-3 px-1.5 text-center">
                                            <select name="character[{{ $cs->id }}][indicators][{{ $ind->id }}]"
                                                class="text-xs font-bold px-1.5 py-1 bg-slate-50 border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500">
                                                <option value="BSB" {{ ($iScores[$ind->id] ?? 'BSB') == 'BSB' ? 'selected' : '' }}>BSB</option>
                                                <option value="B" {{ ($iScores[$ind->id] ?? '') == 'B' ? 'selected' : '' }}>B</option>
                                                <option value="MB" {{ ($iScores[$ind->id] ?? '') == 'MB' ? 'selected' : '' }}>MB</option>
                                                <option value="PB" {{ ($iScores[$ind->id] ?? '') == 'PB' ? 'selected' : '' }}>PB</option>
                                            </select>
                                        </td>
                                    @endforeach

                                    <!-- Mutabaah -->
                                    <td class="py-3 px-3 border-l border-slate-200">
                                        <input type="text" name="character[{{ $cs->id }}][mutabaah_sholat_fardhu]"
                                            value="{{ $grade->mutabaah_sholat_fardhu ?? 'Selalu Berjamaah' }}"
                                            class="w-32 px-2 py-1 bg-slate-50 border border-slate-200 rounded text-[11px] font-medium">
                                    </td>

                                    <td class="py-3 px-3">
                                        <input type="text" name="character[{{ $cs->id }}][mutabaah_sholat_dhuha]"
                                            value="{{ $grade->mutabaah_sholat_dhuha ?? 'Rutin 4 Rakaat' }}"
                                            class="w-32 px-2 py-1 bg-slate-50 border border-slate-200 rounded text-[11px] font-medium">
                                    </td>

                                    <td class="py-3 px-3">
                                        <input type="text" name="character[{{ $cs->id }}][mutabaah_tilawah]"
                                            value="{{ $grade->mutabaah_tilawah ?? '1/2 Juz per Hari' }}"
                                            class="w-32 px-2 py-1 bg-slate-50 border border-slate-200 rounded text-[11px] font-medium">
                                    </td>

                                    <!-- Notes -->
                                    <td class="py-3 px-4">
                                        <textarea name="character[{{ $cs->id }}][bpi_mentor_notes]" rows="2"
                                            class="w-48 p-2 bg-slate-50 border border-slate-200 rounded-lg text-[11px] leading-tight focus:ring-1 focus:ring-emerald-500 focus:outline-none"
                                            placeholder="Catatan perkembangan karakter...">{{ $grade->bpi_mentor_notes ?? 'Menunjukkan teladan akhlak dan kesantunan yang baik.' }}</textarea>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-xs text-slate-500">
                        🌱 <strong>Bina Pribadi Islami (BPI):</strong> Mengukur pembiasaan adab harian, integritas moral, dan keteladanan sosial santri di sekolah maupun rumah.
                    </p>
                    <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-extrabold text-xs shadow-md hover:from-emerald-500 hover:to-teal-500 transition">
                        Simpan Nilai Karakter & Mutabaah
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
