<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
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
            'name' => 'required|string|max:255|unique:areas',
        ]);
        Area::create($validated);
        return back()->with('success', 'Area berhasil ditambahkan.');
    }

    public function show(Area $area)
    {
        $url = route('findings.create', ['area_id' => $area->id]);
        
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)
                    ->color(79, 70, 229) // Indigo-600 color
                    ->generate($url);

        return view('areas.show', compact('area', 'qrCode', 'url'));
    }

    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:areas,name,' . $area->id,
        ]);
        $area->update($validated);
        return back()->with('success', 'Area berhasil diperbarui.');
    }

    public function destroy(Area $area)
    {
        if ($area->findings()->count() > 0) {
            return back()->with('error', 'Area tidak bisa dihapus karena sudah dipakai pada temuan.');
        }
        $area->delete();
        return back()->with('success', 'Area berhasil dihapus.');
    }
}
