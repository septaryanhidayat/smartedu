<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ReportSettingsController extends Controller
{
    protected function getActiveUnit(): SchoolUnit
    {
        $unitId = session('active_school_unit_id');
        if ($unitId) {
            $unit = SchoolUnit::find($unitId);
            if ($unit) return $unit;
        }
        $unit = SchoolUnit::where('code', 'sdit')->first() ?? SchoolUnit::first();
        session(['active_school_unit_id' => $unit->id]);
        return $unit;
    }

    public function index()
    {
        $activeUnit = $this->getActiveUnit();
        $allUnits = SchoolUnit::all();

        return view('admin.settings.report', compact('activeUnit', 'allUnits'));
    }

    public function update(Request $request)
    {
        $activeUnit = $this->getActiveUnit();

        $request->validate([
            'principal_name' => 'required|string|max:255',
            'principal_nip' => 'nullable|string|max:50',
            'quran_coordinator_name' => 'nullable|string|max:255',
            'quran_coordinator_nip' => 'nullable|string|max:50',
            'report_city' => 'required|string|max:100',
            'report_date' => 'required|date',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'letterhead' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'stamp' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'principal_signature' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'quran_coordinator_signature' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        $data = [
            'principal_name' => $request->principal_name,
            'principal_nip' => $request->principal_nip,
            'quran_coordinator_name' => $request->quran_coordinator_name,
            'quran_coordinator_nip' => $request->quran_coordinator_nip,
            'report_city' => $request->report_city,
            'report_date' => $request->report_date,
            'print_settings' => [
                'header_type' => $request->input('header_type', 'text_and_logo'),
                'paper_size' => $request->input('paper_size', 'A4'),
                'show_stamp' => $request->has('show_stamp'),
                'show_signature' => $request->has('show_signature'),
                'signer_mode' => $request->input('signer_mode', 'standard'),
            ]
        ];

        $uploadDir = public_path("uploads/units/{$activeUnit->code}");
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        // Handle File Uploads
        if ($request->hasFile('logo')) {
            $filename = 'logo_' . time() . '.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move($uploadDir, $filename);
            $data['logo_path'] = "uploads/units/{$activeUnit->code}/{$filename}";
        }

        if ($request->hasFile('letterhead')) {
            $filename = 'letterhead_' . time() . '.' . $request->file('letterhead')->getClientOriginalExtension();
            $request->file('letterhead')->move($uploadDir, $filename);
            $data['letterhead_path'] = "uploads/units/{$activeUnit->code}/{$filename}";
        }

        if ($request->hasFile('stamp')) {
            $filename = 'stamp_' . time() . '.' . $request->file('stamp')->getClientOriginalExtension();
            $request->file('stamp')->move($uploadDir, $filename);
            $data['stamp_path'] = "uploads/units/{$activeUnit->code}/{$filename}";
        }

        if ($request->hasFile('principal_signature')) {
            $filename = 'principal_sig_' . time() . '.' . $request->file('principal_signature')->getClientOriginalExtension();
            $request->file('principal_signature')->move($uploadDir, $filename);
            $data['principal_signature_path'] = "uploads/units/{$activeUnit->code}/{$filename}";
        }

        if ($request->hasFile('quran_coordinator_signature')) {
            $filename = 'quran_sig_' . time() . '.' . $request->file('quran_coordinator_signature')->getClientOriginalExtension();
            $request->file('quran_coordinator_signature')->move($uploadDir, $filename);
            $data['quran_coordinator_signature_path'] = "uploads/units/{$activeUnit->code}/{$filename}";
        }

        $activeUnit->update($data);

        return redirect()->route('admin.report-settings.index')->with('success', 'Pengaturan cetak dan legalitas e-rapor berhasil diperbarui.');
    }
}
