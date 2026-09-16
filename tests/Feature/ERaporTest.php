<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\SchoolUnit;
use App\Models\ClassroomStudent;
use App\Models\Classroom;

class ERaporTest extends TestCase
{
    public function test_admin_dashboard_loads(): void
    {
        $admin = User::first();
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('E-RAPOR TERPADU');
    }

    public function test_report_settings_page_loads(): void
    {
        $admin = User::first();
        $response = $this->actingAs($admin)->get(route('admin.report-settings.index'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Kop Surat & Tanda Tangan');
    }

    public function test_grades_pages_load(): void
    {
        $admin = User::first();
        
        $resAcademic = $this->actingAs($admin)->get(route('admin.grades.academic'));
        $resAcademic->assertStatus(200);
        $resAcademic->assertSee('Input Nilai Akademik');

        $resQuran = $this->actingAs($admin)->get(route('admin.grades.quran'));
        $resQuran->assertStatus(200);
        $resQuran->assertSee('Tahsin Wafa');

        $resChar = $this->actingAs($admin)->get(route('admin.grades.character'));
        $resChar->assertStatus(200);
        $resChar->assertSee('7 Standar Kompetensi Lulusan (SKL) JSIT');

        $resHome = $this->actingAs($admin)->get(route('admin.grades.homeroom'));
        $resHome->assertStatus(200);
        $resHome->assertSee('Presensi');
    }

    public function test_report_print_pages_load(): void
    {
        $admin = User::first();
        $cs = ClassroomStudent::first();
        $this->assertNotNull($cs, 'ClassroomStudent exists');

        // Test Print Academic
        $resPrintAcad = $this->actingAs($admin)->get(route('admin.reports.print.academic', $cs->id));
        $resPrintAcad->assertStatus(200);
        $resPrintAcad->assertSee('LAPORAN HASIL BELAJAR (RAPOR AKADEMIK)');

        // Test Print Quran
        $resPrintQuran = $this->actingAs($admin)->get(route('admin.reports.print.quran', $cs->id));
        $resPrintQuran->assertStatus(200);
        $resPrintQuran->assertSee('TAHSIN METODE WAFA');

        // Test Print Character
        $resPrintChar = $this->actingAs($admin)->get(route('admin.reports.print.character', $cs->id));
        $resPrintChar->assertStatus(200);
        $resPrintChar->assertSee('RAPOR MUTU KARAKTER & BINA PRIBADI ISLAMI');

        // Test Print Bundle
        $resPrintBundle = $this->actingAs($admin)->get(route('admin.reports.print.bundle', $cs->id));
        $resPrintBundle->assertStatus(200);
        $resPrintBundle->assertSee('BUKU LAPORAN HASIL BELAJAR PESERTA DIDIK');
        $resPrintBundle->assertSee('BAGIAN I: LAPORAN HASIL BELAJAR');
        $resPrintBundle->assertSee('BAGIAN II: LAPORAN CAPAIAN AL-QUR\'AN');
        $resPrintBundle->assertSee('BAGIAN III: RAPOR MUTU KARAKTER');

        // Test Print Leger
        $resPrintLeger = $this->actingAs($admin)->get(route('admin.reports.print.leger', $cs->classroom_id));
        $resPrintLeger->assertStatus(200);
        $resPrintLeger->assertSee('LEGER NILAI HASIL BELAJAR SISWA');
    }

    public function test_unit_switching_works(): void
    {
        $admin = User::first();
        $smpit = SchoolUnit::where('code', 'smpit')->first();
        $this->assertNotNull($smpit);

        $response = $this->actingAs($admin)->post(route('admin.units.switch', $smpit->id));
        $response->assertSessionHas('active_school_unit_id', $smpit->id);
    }
}
