<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\CarbonPeriod;

class AbsensiExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $employees;
    protected $start;
    protected $end;

    // Bagian ini harus menerima 3 argument:
    public function __construct($employees, $start, $end)
    {
        $this->employees = $employees;
        $this->start = $start;
        $this->end = $end;
    }

    public function title(): string
    {
        return 'Laporan'; 
    }

    public function view(): View
    {
        $dateRange = CarbonPeriod::create($this->start, $this->end);

        return view('absensi.cetak_pdf', [
            'employees' => $this->employees,
            'start' => $this->start,
            'end' => $this->end,
            'dateRange' => $dateRange
        ]);
    }
}