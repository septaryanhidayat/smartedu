@extends('admin.layout')

@section('title', 'Pengaturan Legalitas & Cetak Rapor - ' . $activeUnit->name)

@section('content')
<div class="space-y-8 max-w-6xl">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 border border-teal-200 text-teal-800 text-xs font-bold mb-2">
                <span>⚙️ Legalitas & Format Cetak</span>
                <span>•</span>
                <span class="uppercase tracking-wider">{{ $activeUnit->name }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan Kop Surat & Tanda Tangan E-Rapor</h1>
            <p class="text-xs text-slate-500 mt-1">Konfigurasikan kop surat, logo, stempel resmi, tanda tangan pejabat, dan titimangsa rapor untuk unit <strong>{{ $activeUnit->name }}</strong>.</p>
        </div>
    </div>

    <form action="{{ route('admin.report-settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- 1. Media Visual: Kop Surat, Logo & Stempel -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <span>🖼️</span> <span>Logo, Kop Surat & Stempel Resmi</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Asset visual ini akan otomatis terpasang pada lembar Rapor Akademik, Rapor Al-Qur'an, dan Rapor Karakter.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Logo Sekolah -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col justify-between">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">1. Logo Sekolah (PNG / SVG)</label>
                        <p class="text-[11px] text-slate-500 mb-3">Tampil di sudut kiri kop surat dan sampul rapor. Dianjurkan latar belakang transparan.</p>
                        @if($activeUnit->logo_path)
                            <div class="mb-3 p-3 bg-white rounded-xl border border-slate-200 inline-block">
                                <img src="{{ asset($activeUnit->logo_path) }}" alt="Logo {{ $activeUnit->name }}" class="h-16 w-auto object-contain">
                            </div>
                        @endif
                    </div>
                    <div>
                        <input type="file" name="logo" accept="image/*" class="w-full text-xs text-slate-600 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                    </div>
                </div>

                <!-- Kop Surat Header -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col justify-between">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">2. Kop Surat Banner (Opsional)</label>
                        <p class="text-[11px] text-slate-500 mb-3">Gambar kop surat instansi utuh (rasio horizontal). Jika kosong, sistem otomatis membuat kop teks standar.</p>
                        @if($activeUnit->letterhead_path)
                            <div class="mb-3 p-2 bg-white rounded-xl border border-slate-200">
                                <img src="{{ asset($activeUnit->letterhead_path) }}" alt="Kop Surat" class="h-14 w-full object-contain">
                            </div>
                        @endif
                    </div>
                    <div>
                        <input type="file" name="letterhead" accept="image/*" class="w-full text-xs text-slate-600 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-teal-600 file:text-white hover:file:bg-teal-700 cursor-pointer">
                    </div>
                </div>

                <!-- Stempel Sekolah -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col justify-between">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">3. Stempel Resmi Sekolah (PNG)</label>
                        <p class="text-[11px] text-slate-500 mb-3">Stempel cap basah sekolah (transparan) yang ditumpuk di atas tanda tangan Kepala Sekolah.</p>
                        @if($activeUnit->stamp_path)
                            <div class="mb-3 p-3 bg-white rounded-xl border border-slate-200 inline-block">
                                <img src="{{ asset($activeUnit->stamp_path) }}" alt="Stempel" class="h-16 w-auto object-contain">
                            </div>
                        @endif
                    </div>
                    <div>
                        <input type="file" name="stamp" accept="image/*" class="w-full text-xs text-slate-600 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Pejabat Penandatangan & Tanda Tangan Digital -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <span>✍️</span> <span>Pejabat Penandatangan & Tanda Tangan Digital</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Atur nama pejabat yang tertera pada lembar legalitas akhir rapor.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Kepala Sekolah -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-extrabold uppercase text-slate-800 tracking-wider">A. Kepala Sekolah</h3>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Penandatangan Utama</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar</label>
                        <input type="text" name="principal_name" value="{{ old('principal_name', $activeUnit->principal_name) }}" required
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">NIP / NIY Kepala Sekolah</label>
                        <input type="text" name="principal_nip" value="{{ old('principal_nip', $activeUnit->principal_nip) }}"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Contoh: 198205142008011005">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Upload Tanda Tangan Digital (PNG Transparan)</label>
                        @if($activeUnit->principal_signature_path)
                            <div class="mb-2 p-2 bg-white rounded-lg border border-slate-200 inline-block">
                                <img src="{{ asset($activeUnit->principal_signature_path) }}" alt="TTD Kepala Sekolah" class="h-12 w-auto object-contain">
                            </div>
                        @endif
                        <input type="file" name="principal_signature" accept="image/*" class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-white hover:file:bg-slate-900 cursor-pointer">
                    </div>
                </div>

                <!-- Koordinator Al-Qur'an -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-extrabold uppercase text-slate-800 tracking-wider">B. Koordinator Al-Qur'an / Wafa</h3>
                        <span class="px-2 py-0.5 rounded-full bg-teal-100 text-teal-800 text-[10px] font-bold">Rapor Al-Qur'an</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar</label>
                        <input type="text" name="quran_coordinator_name" value="{{ old('quran_coordinator_name', $activeUnit->quran_coordinator_name) }}"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-teal-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">NIP / NIY Koordinator Al-Qur'an</label>
                        <input type="text" name="quran_coordinator_nip" value="{{ old('quran_coordinator_nip', $activeUnit->quran_coordinator_nip) }}"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-teal-500 focus:outline-none" placeholder="Contoh: 198709122014021003">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Upload Tanda Tangan Digital (PNG Transparan)</label>
                        @if($activeUnit->quran_coordinator_signature_path)
                            <div class="mb-2 p-2 bg-white rounded-lg border border-slate-200 inline-block">
                                <img src="{{ asset($activeUnit->quran_coordinator_signature_path) }}" alt="TTD Koordinator Quran" class="h-12 w-auto object-contain">
                            </div>
                        @endif
                        <input type="file" name="quran_coordinator_signature" accept="image/*" class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-white hover:file:bg-slate-900 cursor-pointer">
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Titimangsa & Opsi Format Cetak -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <span>📅</span> <span>Titimangsa & Format Dokumen</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Tempat dan tanggal penerbitan rapor serta preferensi cetak otomatis.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kota / Tempat Terbit Rapor</label>
                    <input type="text" name="report_city" value="{{ old('report_city', $activeUnit->report_city ?? 'Palembang') }}" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <p class="text-[11px] text-slate-400 mt-1">Akan tercetak di bagian ttd, contoh: "Palembang, 20 Desember 2025"</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Pembagian Rapor</label>
                    <input type="date" name="report_date" value="{{ old('report_date', optional($activeUnit->report_date)->format('Y-m-d') ?? '2025-12-20') }}" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Preferensi Cetak Otomatis</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-emerald-500 transition">
                        <input type="checkbox" name="show_signature" value="1" {{ ($activeUnit->print_settings['show_signature'] ?? true) ? 'checked' : '' }}
                            class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4">
                        <span class="text-xs font-semibold text-slate-700">Tampilkan Tanda Tangan Digital</span>
                    </label>

                    <label class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-emerald-500 transition">
                        <input type="checkbox" name="show_stamp" value="1" {{ ($activeUnit->print_settings['show_stamp'] ?? true) ? 'checked' : '' }}
                            class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4">
                        <span class="text-xs font-semibold text-slate-700">Tampilkan Stempel Resmi</span>
                    </label>

                    <div class="p-3 bg-white rounded-xl border border-slate-200 flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-700">Ukuran Kertas Rapor</span>
                        <select name="paper_size" class="text-xs font-bold bg-slate-50 border border-slate-300 rounded-lg px-2 py-1">
                            <option value="A4" {{ ($activeUnit->print_settings['paper_size'] ?? 'A4') == 'A4' ? 'selected' : '' }}>A4 (210 x 297 mm)</option>
                            <option value="F4" {{ ($activeUnit->print_settings['paper_size'] ?? '') == 'F4' ? 'selected' : '' }}>F4 / Folio (215 x 330 mm)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4">
            <button type="submit" class="px-8 py-3.5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-extrabold text-xs shadow-lg shadow-emerald-700/20 hover:from-emerald-500 hover:to-teal-500 transition transform active:scale-98 flex items-center gap-2">
                <span>💾</span>
                <span>Simpan Pengaturan Legalitas & Cetak</span>
            </button>
        </div>
    </form>
</div>
@endsection
