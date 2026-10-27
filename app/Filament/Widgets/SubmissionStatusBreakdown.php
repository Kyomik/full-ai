<?php

namespace App\Filament\Widgets;

use App\Models\CulturalEvent;

class SubmissionStatusBreakdown extends DonutBreakdown
{
    protected static ?string $heading = 'Status Pengajuan';

    public function getColumnSpan(): int|string|array
    {
        return ['default' => 1, 'md' => 5];
    }

    public static function canView(): bool
    {
        return auth()->user()?->role === 'agency_admin';
    }

    public static function getSort(): int
    {
        return 3;
    }

    protected function getBreakdown(): array
    {
        $counts = CulturalEvent::query()
            ->where('agency_id', auth()->user()?->agency_id)
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return [
            'Menunggu Verifikasi' => (int) ($counts['pending'] ?? 0),
            'Disetujui' => (int) ($counts['approved'] ?? 0),
            'Ditolak' => (int) ($counts['rejected'] ?? 0),
        ];
    }
}
