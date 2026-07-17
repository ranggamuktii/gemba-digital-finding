<?php

namespace App\Http\Controllers;

use App\Models\Finding;
use Illuminate\Http\Request;

class FindingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $findings = Finding::with(['category', 'area', 'riskLevel', 'creator', 'assignee'])->select('findings.*');
            return \Yajra\DataTables\Facades\DataTables::of($findings)
                ->editColumn('created_at', function($finding) {
                    return $finding->created_at->format('Y-m-d H:i');
                })
                ->addColumn('action', function($finding) {
                    return '<a href="'.route('findings.show', $finding->id).'" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">View &rarr;</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('findings.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('findings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'area_id' => 'required|exists:areas,id',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'risk_level_id' => 'required|exists:risk_levels,id',
            'photo' => 'nullable|image|max:2048',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        try {
            $finding = \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request) {
                $riskLevel = \App\Models\RiskLevel::findOrFail($validated['risk_level_id']);
                $validated['due_date'] = now()->addHours($riskLevel->sla_hours);
                $validated['created_by'] = auth()->id();
                $validated['status'] = 'OPEN';

                if ($request->hasFile('photo')) {
                    $validated['photo'] = $request->file('photo')->store('findings', 'public');
                }

                return Finding::create($validated);
            });

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Finding created successfully', 'data' => $finding], 201);
            }

            return redirect()->route('findings.index')->with('success', 'Finding created successfully.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to create finding: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal membuat tiket: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Finding $finding)
    {
        return view('findings.show', compact('finding'));
    }

    /**
     * Export findings to Excel or PDF.
     */
    public function export(Request $request)
    {
        $type = $request->query('type', 'xlsx');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        
        $export = new \App\Exports\FindingsExport($startDate, $endDate);

        if ($type === 'pdf') {
            return \Maatwebsite\Excel\Facades\Excel::download($export, 'findings-report.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }

        return \Maatwebsite\Excel\Facades\Excel::download($export, 'findings-report.xlsx');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Finding $finding)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Finding $finding)
    {
        if ($finding->status === 'CLOSED' && !auth()->user()->hasRole('Super Admin')) {
            abort(403, 'This finding is already closed and locked.');
        }

        if ($request->has('status') && $request->status === 'CLOSED') {
            \Illuminate\Support\Facades\Gate::authorize('verify', $finding);
        } else {
            \Illuminate\Support\Facades\Gate::authorize('update', $finding);
        }

        $validated = $request->validate([
            'status' => 'sometimes|required|in:OPEN,IN_PROGRESS,WAITING_VERIFICATION,CLOSED,OVERDUE',
            'assigned_to' => 'sometimes|nullable|exists:users,id',
        ]);

        $finding->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Finding updated successfully', 'data' => $finding]);
        }

        return redirect()->route('findings.show', $finding)->with('success', 'Finding updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Finding $finding)
    {
        //
    }
}
