<?php

namespace App\Exports;

use App\Models\Finding;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FindingsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Finding::with(['category', 'area', 'riskLevel', 'creator', 'assignee']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Finding No',
            'Category',
            'Area',
            'Location',
            'Problem',
            'Risk Level',
            'Status',
            'Creator',
            'PIC',
            'Due Date',
            'Created At',
        ];
    }

    public function map($finding): array
    {
        return [
            $finding->finding_no,
            $finding->category->name ?? '-',
            $finding->area->name ?? '-',
            $finding->location,
            $finding->description,
            $finding->riskLevel->name ?? '-',
            $finding->status,
            $finding->creator->name ?? '-',
            $finding->assignee->name ?? '-',
            $finding->due_date ? $finding->due_date->format('Y-m-d H:i') : '-',
            $finding->created_at->format('Y-m-d H:i'),
        ];
    }
}
