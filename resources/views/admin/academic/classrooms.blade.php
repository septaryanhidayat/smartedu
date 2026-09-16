@extends('admin.layout')

@section('title', 'Rombongan Belajar (Kelas) - ' . $activeUnit->name)

@section('content')
<div class="space-y-8 max-w-6xl">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 border border-teal-200 text-teal-800 text-xs font-bold mb-2">
                <span>🗂️ Master Akademik</span>
                <span>•</span>
                <span class="uppercase tracking-wider">{{ $activeUnit->name }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Rombongan Belajar (Kelas)</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar kelas aktif semester ganjil 2025/2026 pada unit <strong>{{ $activeUnit->name }}</strong>.</p>
        </div>
    </div>

    <!-- Classrooms Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classrooms as $c)
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 hover:shadow-md transition flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-700 font-extrabold text-[11px] border border-emerald-200">
                            Fase {{ $c->phase }} (Tingkat {{ $c->grade_level }})
                        </span>
                        <span class="text-xs font-bold text-slate-400">
                            {{ $c->classroomStudents->count() }} Santri/Siswa
                        </span>
                    </div>

                    <div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight">{{ $c->name }}</h3>
                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                            <span>👨‍🏫 Wali Kelas:</span>
                            <span class="font-bold text-slate-700">{{ $c->homeroomTeacher->name ?? 'Belum Ditentukan' }}</span>
                        </p>
                    </div>
                </div>

                <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-between gap-2">
                    <a href="{{ route('admin.academic.classrooms.students', $c->id) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                        <span>👥</span> <span>Daftar Siswa</span>
                    </a>

                    <a href="{{ route('admin.reports.index', ['classroom_id' => $c->id]) }}" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center gap-1.5">
                        <span>🖨️</span> <span>Cetak Rapor</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full p-12 bg-white rounded-3xl border border-slate-200 text-center">
                <p class="text-sm font-bold text-slate-500">Belum ada rombongan belajar di unit ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
