<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolUnit;
use Illuminate\Http\Request;

class UnitSwitchController extends Controller
{
    public function switch(Request $request, $id)
    {
        $unit = SchoolUnit::findOrFail($id);
        session(['active_school_unit_id' => $unit->id]);

        return redirect()->back()->with('success', "Berhasil beralih ke unit: {$unit->name}");
    }
}
