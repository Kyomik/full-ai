<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportSheet implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles, WithTitle
{
    /**
     * @param array<string, string> $fields
     * @param array<int, array<string, mixed>> $rows
     */
    public function __construct(
        private readonly string $sheetTitle,
        private readonly array $fields,
        private readonly array $rows,
    ) {}

    public function collection(): Collection
    {
        return collect($this->rows)->map(fn (array $row): array => array_map(
            fn (string $field): mixed => $row[$field] ?? '',
            array_keys($this->fields),
        ));
    }

    public function headings(): array
    {
        return array_values($this->fields);
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '166534']],
            ],
        ];
    }
}
