@extends('admin.layout')

@section('title', 'Dashboard Overview - SmartEdu SIT')

@section('content')
<div class="space-y-8 max-w-6xl">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold mb-2">
                <span>🏫 Platform SIM Sekolah Islam Terpadu</span>
                <span>•</span>
                <span class="uppercase tracking-wider">{{ $currentUnit->name ?? 'SIT SmartEdu' }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard Manajemen & E-Rapor Terpadu</h1>
            <p class="text-xs text-slate-600 font-medium mt-1">Sistem informasi akademik, penilaian Kurikulum Merdeka, Metode Wafa, dan Standar Mutu Karakter JSIT.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reports.index') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-extrabold text-xs shadow-md hover:from-emerald-500 hover:to-teal-500 transition flex items-center gap-2">
                <span>🖨️</span> <span>Pusat Cetak E-Rapor</span>
            </a>
        </div>
    </div>

    <!-- E-Rapor Quick Access Banner -->
    <div class="p-6 md:p-8 rounded-3xl bg-gradient-to-r from-slate-900 via-teal-950 to-slate-900 text-white border border-slate-800 shadow-xl relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3 max-w-xl">
                <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 font-extrabold text-[11px] border border-emerald-500/30 uppercase tracking-wider">
                    Fitur Unggulan E-Rapor SIT
                </span>
                <h2 class="text-xl md:text-2xl font-black text-white tracking-tight">
                    Dual Kurikulum: Merdeka + Wafa + 7 SKL JSIT
                </h2>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Sistem siap mencetak rapor dalam format <strong>Cetak Terpisah</strong> (Akademik, Qur'an, Karakter) maupun <strong>Cetak Gabungan All-in-One</strong> dengan kop surat, logo, dan tanda tangan digital terverifikasi.
                </p>
                <div class="pt-2 flex flex-wrap gap-2.5 text-xs font-bold">
                    <a href="{{ route('admin.grades.academic') }}" class="px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white transition flex items-center gap-1.5">
                        <span>📝</span> <span>Nilai Akademik</span>
                    </a>
                    <a href="{{ route('admin.grades.quran') }}" class="px-3 py-1.5 rounded-lg bg-teal-500/30 hover:bg-teal-500/40 text-teal-200 transition border border-teal-500/30 flex items-center gap-1.5">
                        <span>📖</span> <span>Qur'an Wafa</span>
                    </a>
                    <a href="{{ route('admin.grades.character') }}" class="px-3 py-1.5 rounded-lg bg-indigo-500/30 hover:bg-indigo-500/40 text-indigo-200 transition border border-indigo-500/30 flex items-center gap-1.5">
                        <span>🌙</span> <span>Karakter JSIT</span>
                    </a>
                    <a href="{{ route('admin.report-settings.index') }}" class="px-3 py-1.5 rounded-lg bg-emerald-500/30 hover:bg-emerald-500/40 text-emerald-200 transition border border-emerald-500/30 flex items-center gap-1.5">
                        <span>⚙️</span> <span>Legalitas & Kop</span>
                    </a>
                </div>
            </div>

            <div class="shrink-0 text-center p-5 bg-white/5 backdrop-blur rounded-2xl border border-white/10">
                <span class="text-3xl">🖨️</span>
                <p class="text-[10px] text-slate-400 font-bold uppercase mt-2">Status Unit Aktif</p>
                <p class="text-sm font-black text-emerald-400">{{ $currentUnit->name ?? 'SDIT SmartEdu' }}</p>
                <p class="text-[10px] text-slate-400 mt-1">Titimangsa: {{ $currentUnit->report_city ?? 'Palembang' }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @php
            $activeClassCount = \App\Models\Classroom::where('school_unit_id', $currentUnit->id ?? 1)->count();
            $activeStudentCount = \App\Models\Student::where('school_unit_id', $currentUnit->id ?? 1)->count();
            $activeSubjectCount = \App\Models\Subject::where('school_unit_id', $currentUnit->id ?? 1)->count();
        @endphp

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 text-xl flex items-center justify-center font-bold">🗂️</div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase">Rombel / Kelas</p>
                <h3 class="text-lg font-black text-slate-900">{{ $activeClassCount }} Kelas</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-800 text-xl flex items-center justify-center font-bold">👥</div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase">Santri / Siswa</p>
                <h3 class="text-lg font-black text-slate-900">{{ $activeStudentCount }} Santri</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-800 text-xl flex items-center justify-center font-bold">📚</div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase">Mata Pelajaran</p>
                <h3 class="text-lg font-black text-slate-900">{{ $activeSubjectCount }} Mapel</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 text-xl flex items-center justify-center font-bold">🧩</div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase">Modul Sistem</p>
                <h3 class="text-lg font-black text-slate-900">{{ $moduleCount ?? 21 }} Modul</h3>
            </div>
        </div>
    </div>

    <!-- Modules List Quick Access -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-base text-slate-900">Modul Fitur Terintegrasi SmartEdu</h2>
            <a href="{{ route('admin.modules.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">Kelola Modul →</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($recentModules as $mod)
            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-xl">{{ $mod->icon }}</span>
                    <div>
                        <h4 class="font-extrabold text-xs text-slate-900">{{ $mod->title }}</h4>
                        <p class="text-[11px] text-slate-500 line-clamp-1">{{ $mod->short_desc }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.modules.edit', $mod->id) }}" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-slate-700 font-bold text-[11px] hover:bg-slate-100">Sunting</a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
