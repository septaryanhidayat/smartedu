@extends('admin.layout')

@section('title', 'Mata Pelajaran & Bank TP - ' . $activeUnit->name)

@section('content')
<div class="space-y-8 max-w-6xl">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 border border-teal-200 text-teal-800 text-xs font-bold mb-2">
                <span>📚 Kurikulum & Mapel</span>
                <span>•</span>
                <span class="uppercase tracking-wider">{{ $activeUnit->name }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Mata Pelajaran & Tujuan Pembelajaran (TP)</h1>
            <p class="text-xs text-slate-500 mt-1">Struktur kurikulum nasional terpadu dan kekhasan Diniyah SIT pada unit <strong>{{ $activeUnit->name }}</strong>.</p>
        </div>
    </div>

    <!-- Subjects Grouped -->
    <div class="space-y-6">
        @php
            $grouped = $subjects->groupBy('category');
            $categoryLabels = [
                'nasional' => '1. Kelompok Mata Pelajaran Nasional (Kemendikbud)',
                'diniyah_sit' => '2. Kelompok Mata Pelajaran Kekhasan Diniyah SIT',
                'muatan_lokal' => '3. Kelompok Muatan Lokal',
            ];
        @endphp

        @foreach(['nasional', 'diniyah_sit', 'muatan_lokal'] as $cat)
            @if(isset($grouped[$cat]))
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">
                            {{ $categoryLabels[$cat] }}
                        </h2>
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">
                            {{ $grouped[$cat]->count() }} Mata Pelajaran
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($grouped[$cat] as $subj)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="font-mono text-[10px] font-extrabold px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800">
                                            {{ $subj->code }}
                                        </span>
                                        <h3 class="font-bold text-slate-900 text-sm mt-1">{{ $subj->name }}</h3>
                                    </div>
                                    <span class="text-[11px] font-semibold text-slate-400">
                                        {{ $subj->learningObjectives->count() }} TP Aktif
                                    </span>
                                </div>

                                <!-- Learning Objectives List -->
                                <div class="space-y-1.5 pt-2 border-t border-slate-200">
                                    <p class="text-[10px] uppercase font-extrabold text-slate-400">Tujuan Pembelajaran (TP):</p>
                                    @forelse($subj->learningObjectives as $tp)
                                        <div class="text-[11px] bg-white p-2 rounded-xl border border-slate-200 text-slate-700 flex items-start gap-2">
                                            <span class="font-bold text-teal-700 shrink-0">{{ $tp->code }}:</span>
                                            <span>{{ $tp->description }}</span>
                                        </div>
                                    @empty
                                        <p class="text-[11px] italic text-slate-400">Belum ada TP terdaftar untuk mapel ini.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
@endsection
