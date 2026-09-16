@extends('admin.layout')

@section('title', 'Daftar Siswa ' . $classroom->name)

@section('content')
<div class="space-y-8 max-w-6xl">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 border border-teal-200 text-teal-800 text-xs font-bold mb-2">
                <a href="{{ route('admin.academic.classrooms') }}" class="hover:underline">← Kembali ke Rombel</a>
                <span>•</span>
                <span>{{ $classroom->name }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Santri / Siswa Kelas {{ $classroom->name }}</h1>
            <p class="text-xs text-slate-500 mt-1">Tahun Ajaran {{ $classroom->academicYear->name }} • Semester {{ ucfirst($classroom->academicYear->semester) }}</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reports.index', ['classroom_id' => $classroom->id]) }}" class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl text-xs font-bold shadow-md hover:from-emerald-500 hover:to-teal-500 transition flex items-center gap-2">
                <span>🖨️</span> <span>Cetak Rapor Kelas Ini</span>
            </a>
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-extrabold uppercase border-b border-slate-200 text-[10px] tracking-wider">
                    <tr>
                        <th class="py-4 px-6">No</th>
                        <th class="py-4 px-6">Nama Santri / Siswa</th>
                        <th class="py-4 px-6">NIS / NISN</th>
                        <th class="py-4 px-6">L/P</th>
                        <th class="py-4 px-6">Rata-Rata Nilai</th>
                        <th class="py-4 px-6 text-center">Aksi Cepat Rapor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($classroom->classroomStudents as $idx => $cs)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-6 font-bold text-slate-900">{{ $idx + 1 }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $cs->student->photo_url }}" alt="{{ $cs->student->full_name }}" class="w-9 h-9 rounded-xl object-cover shadow-sm">
                                    <div>
                                        <p class="font-extrabold text-slate-900 text-sm">{{ $cs->student->full_name }}</p>
                                        <p class="text-[11px] text-slate-400">Panggilan: {{ $cs->student->nickname ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 font-mono text-slate-700">
                                <p><span class="text-[10px] text-slate-400">NIS:</span> {{ $cs->student->nis ?? '-' }}</p>
                                <p><span class="text-[10px] text-slate-400">NISN:</span> {{ $cs->student->nisn ?? '-' }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 rounded-lg text-[11px] font-extrabold {{ $cs->student->gender == 'L' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-pink-50 text-pink-700 border border-pink-200' }}">
                                    {{ $cs->student->gender == 'L' ? 'Ikhwan (L)' : 'Akhwat (P)' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-800 font-black text-xs border border-emerald-200">
                                    {{ $cs->calculateAverageGrade() }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.reports.print.bundle', $cs->id) }}" target="_blank"
                                        class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm transition flex items-center gap-1">
                                        <span>📑</span> <span>Cetak Gabungan</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 font-bold">Belum ada siswa yang didaftarkan di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
