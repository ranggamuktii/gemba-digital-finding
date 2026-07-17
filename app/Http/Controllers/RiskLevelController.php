<?php

namespace App\Http\Controllers;

use App\Models\RiskLevel;
use Illuminate\Http\Request;

class RiskLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:risk_levels',
            'sla_hours' => 'required|integer|min:1',
        ]);
        RiskLevel::create($validated);
        return back()->with('success', 'Risk Level berhasil ditambahkan.');
    }

    public function update(Request $request, RiskLevel $riskLevel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:risk_levels,name,' . $riskLevel->id,
            'sla_hours' => 'required|integer|min:1',
        ]);
        $riskLevel->update($validated);
        return back()->with('success', 'Risk Level berhasil diperbarui.');
    }

    public function destroy(RiskLevel $riskLevel)
    {
        if ($riskLevel->findings()->count() > 0) {
            return back()->with('error', 'Risk Level tidak bisa dihapus karena sudah dipakai pada temuan.');
        }
        $riskLevel->delete();
        return back()->with('success', 'Risk Level berhasil dihapus.');
    }
}
