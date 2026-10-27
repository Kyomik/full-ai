<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportsWorkbook implements WithMultipleSheets
{
    /** @param array<int, ReportSheet> $sheets */
    public function __construct(private readonly array $sheets) {}

    public function sheets(): array
    {
        return $this->sheets;
    }
}
