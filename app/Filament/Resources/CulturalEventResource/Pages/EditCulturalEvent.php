<?php

namespace App\Filament\Resources\CulturalEventResource\Pages;

use App\Filament\Resources\CulturalEventResource;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\VerificationQueue;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\Url;

class EditCulturalEvent extends EditRecord
{
    protected static string $resource = CulturalEventResource::class;

    #[Url(as: 'from')]
    public ?string $source = null;

    public function getBreadcrumbs(): array
    {
        $recordTitle = $this->record?->title ?: 'Edit Festival & Event';
        $dashboardUrl = Dashboard::getUrl();

        if ($this->source === 'verification') {
            return [
                $dashboardUrl => 'Dashboard',
                VerificationQueue::getUrl() => 'Verifikasi Pengajuan',
                $recordTitle,
            ];
        }

        if ($this->source === 'submissions') {
            return [
                $dashboardUrl => 'Dashboard',
                CulturalEventResource::getUrl('index') => 'Daftar Pengajuan',
                $recordTitle,
            ];
        }

        if ($this->source === 'dashboard') {
            return [
                $dashboardUrl => 'Dashboard',
                $recordTitle,
            ];
        }

        return [
            $dashboardUrl => 'Dashboard',
            CulturalEventResource::getUrl('index') => 'Festival & Event',
            $recordTitle,
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (auth()->user()->role === 'agency_admin') {
            $data['status'] = 'pending';
            $data['reviewed_by'] = null;
            $data['reviewed_at'] = null;
            $data['review_note'] = null;
        }
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
