<?php

namespace App\Http\Controllers;

use App\Models\FindingAction;
use App\Models\Finding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class FindingActionController extends Controller
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Finding $finding)
    {
        if ($finding->status === 'CLOSED') {
            abort(403, 'Cannot submit an action for a finding that has already been closed.');
        }

        Gate::authorize('createAction', $finding);

        $validated = $request->validate([
            'action_description' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        try {
            DB::transaction(function () use ($validated, $request, $finding) {
                if ($request->hasFile('photo')) {
                    $validated['photo'] = $request->file('photo')->store('actions', 'public');
                }

                $validated['performed_by'] = auth()->id();
                $validated['action_date'] = now();

                $finding->actions()->create($validated);
                $finding->update(['status' => 'WAITING_VERIFICATION']);
            });

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Action submitted successfully'], 201);
            }

            return redirect()->route('findings.show', $finding)->with('success', 'Tindakan perbaikan berhasil disubmit. Menunggu verifikasi.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to submit action: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal submit tindakan perbaikan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FindingAction $findingAction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FindingAction $findingAction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FindingAction $findingAction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FindingAction $findingAction)
    {
        //
    }
}
