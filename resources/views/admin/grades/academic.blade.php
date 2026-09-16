@extends('admin.layout')

@section('title', 'Input Nilai Akademik Kurikulum Merdeka - ' . $activeUnit->name)

@section('content')
<div class="space-y-8 max-w-7xl">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold mb-2">
                <span>📝 Penilaian Kurikulum Merdeka</span>
                <span>•</span>
                <span class="uppercase tracking-wider">{{ $activeUnit->name }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Input Nilai Akademik & Generator Deskripsi TP</h1>
            <p class="text-xs text-slate-500 mt-1">Isi skor formatif per TP, nilai sumatif, dan sistem otomatis merangkai capaian kompetensi tertinggi & terendah santri.</p>
        </div>
    </div>

    <!-- Filters (Classroom & Subject Selector) -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.grades.academic') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">1. Pilih Rombongan Belajar (Kelas)</label>
                <select name="classroom_id" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-bold focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    @foreach($classrooms as $c)
                        <option value="{{ $c->id }}" {{ ($selectedClassroom && $selectedClassroom->id == $c->id) ? 'selected' : '' }}>
                            {{ $c->name }} (Fase {{ $c->phase }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">2. Pilih Mata Pelajaran</label>
                <select name="subject_id" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-bold focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}" {{ ($selectedSubject && $selectedSubject->id == $s->id) ? 'selected' : '' }}>
                            [{{ $s->code }}] {{ $s->name }} ({{ ucfirst(str_replace('_', ' ', $s->category)) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-slate-900 text-white hover:bg-slate-800 text-xs font-bold transition flex items-center justify-center gap-2">
                    <span>🔍</span> <span>Tampilkan Lembar Nilai</span>
                </button>
            </div>
        </form>
    </div>

    @if($selectedClassroom && $selectedSubject)
        <form action="{{ route('admin.grades.academic.save') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="classroom_id" value="{{ $selectedClassroom->id }}">
            <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">

            <!-- Grade Entry Table -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">{{ $selectedSubject->name }} — {{ $selectedClassroom->name }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Tersedia {{ $learningObjectives->count() }} Tujuan Pembelajaran (TP) terdaftar.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 text-xs text-emerald-700 font-bold bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
                            <span>⚡</span> <span>Smart Auto-Narrative Aktif</span>
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-700 font-extrabold uppercase border-b border-slate-200 text-[10px] tracking-wider">
                            <tr>
                                <th class="py-4 px-4 w-12 text-center">No</th>
                                <th class="py-4 px-4 w-52">Nama Siswa</th>
                                @foreach($learningObjectives as $tp)
                                    <th class="py-4 px-3 text-center" title="{{ $tp->description }}">
                                        {{ $tp->code }}
                                    </th>
                                @endforeach
                                <th class="py-4 px-3 text-center w-24">Sumatif Materi</th>
                                <th class="py-4 px-3 text-center w-24">Sumatif Akhir</th>
                                <th class="py-4 px-3 text-center w-24 font-black text-emerald-800">Nilai Akhir</th>
                                <th class="py-4 px-4">Deskripsi Capaian Tertinggi & Terendah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @foreach($studentsData as $idx => $item)
                                @php
                                    $cs = $item['classroom_student'];
                                    $grade = $item['grade'];
                                    $fScores = $item['formative_scores'];
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition align-top">
                                    <td class="py-4 px-4 text-center font-bold text-slate-900">{{ $idx + 1 }}</td>
                                    <td class="py-4 px-4">
                                        <p class="font-extrabold text-slate-900 text-xs">{{ $cs->student->full_name }}</p>
                                        <p class="text-[10px] text-slate-400">NIS: {{ $cs->student->nis ?? '-' }}</p>
                                    </td>

                                    <!-- Formative Inputs -->
                                    @foreach($learningObjectives as $tp)
                                        <td class="py-3 px-2 text-center">
                                            <input type="number" step="0.1" min="0" max="100"
                                                name="grades[{{ $cs->id }}][formative][{{ $tp->id }}]"
                                                value="{{ $fScores[$tp->id] ?? 85 }}"
                                                class="w-14 px-2 py-1.5 text-center bg-slate-50 border border-slate-300 rounded-lg text-xs font-bold focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                                        </td>
                                    @endforeach

                                    <!-- Sumatif Materi (ASLM) -->
                                    <td class="py-3 px-2 text-center">
                                        <input type="number" step="0.1" min="0" max="100"
                                            name="grades[{{ $cs->id }}][score_sumative_material]"
                                            value="{{ $grade->score_sumative_material ?? 86 }}"
                                            class="w-16 px-2 py-1.5 text-center bg-slate-50 border border-slate-300 rounded-lg text-xs font-bold focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                                    </td>

                                    <!-- Sumatif Akhir (SAS) -->
                                    <td class="py-3 px-2 text-center">
                                        <input type="number" step="0.1" min="0" max="100"
                                            name="grades[{{ $cs->id }}][score_sumative_final]"
                                            value="{{ $grade->score_sumative_final ?? 88 }}"
                                            class="w-16 px-2 py-1.5 text-center bg-slate-50 border border-slate-300 rounded-lg text-xs font-bold focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                                    </td>

                                    <!-- Final Grade -->
                                    <td class="py-3 px-2 text-center">
                                        <input type="number" step="0.1" min="0" max="100"
                                            name="grades[{{ $cs->id }}][final_grade]"
                                            value="{{ $grade->final_grade ?? 87 }}"
                                            class="w-16 px-2 py-1.5 text-center bg-emerald-50 text-emerald-900 border border-emerald-300 rounded-lg text-xs font-black focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                                    </td>

                                    <!-- Auto-Narrative (Tertinggi & Terendah) -->
                                    <td class="py-3 px-4 space-y-2">
                                        <div>
                                            <span class="text-[9px] font-extrabold uppercase text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">Capaian Tertinggi:</span>
                                            <textarea name="grades[{{ $cs->id }}][highest_achievement]" rows="2"
                                                class="w-full mt-1 p-2 bg-slate-50 border border-slate-200 rounded-lg text-[11px] leading-tight focus:ring-1 focus:ring-emerald-500 focus:outline-none"
                                                placeholder="Kosongkan untuk generate otomatis dari TP tertinggi...">{{ $grade->highest_achievement ?? '' }}</textarea>
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-extrabold uppercase text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded">Perlu Bimbingan:</span>
                                            <textarea name="grades[{{ $cs->id }}][lowest_achievement]" rows="2"
                                                class="w-full mt-1 p-2 bg-slate-50 border border-slate-200 rounded-lg text-[11px] leading-tight focus:ring-1 focus:ring-amber-500 focus:outline-none"
                                                placeholder="Kosongkan untuk generate otomatis dari TP terendah...">{{ $grade->lowest_achievement ?? '' }}</textarea>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-xs text-slate-500">
                        💡 <strong>Tips Guru:</strong> Jika kolom deskripsi dibiarkan kosong, sistem akan secara cerdas menyusun kalimat capaian otomatis berdasarkan skor TP tertinggi dan terendah masing-masing siswa.
                    </p>
                    <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-extrabold text-xs shadow-md hover:from-emerald-500 hover:to-teal-500 transition">
                        Simpan Nilai & Generate Deskripsi
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
