<?php

namespace App\Filament\Pages;

use App\Models\EventDocument;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

class SupportingDocuments extends Page
{
    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Dokumen Pendukung';
    protected static ?string $title = 'Dokumen Pendukung';
    protected static ?int $navigationSort = 5;
    protected static string $view = 'filament.pages.supporting-documents';

    public string $search = '';
    public string $statusFilter = '';
    public string $typeFilter = '';
    private ?LengthAwarePaginator $resolvedDocuments = null;

    public function getBreadcrumbs(): array
    {
        return [
            Dashboard::getUrl() => 'Dashboard',
            'Dokumen Pendukung',
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'agency_admin';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola berkas dan dokumen persyaratan yang diunggah untuk pengajuan festival & event Anda.';
    }

    public function getDocumentsProperty(): LengthAwarePaginator
    {
        if ($this->resolvedDocuments) {
            return $this->resolvedDocuments;
        }

        $agencyId = auth()->user()?->agency_id ?? 0;

        return $this->resolvedDocuments = EventDocument::query()
            ->whereHas('event', function ($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->with(['event:id,title,status'])
            ->when($this->search !== '', function (Builder $query): void {
                $term = '%' . addcslashes($this->search, '%_') . '%';
                $query->where(function (Builder $query) use ($term): void {
                    $query->where('name', 'like', $term)
                        ->orWhereHas('event', fn (Builder $eventQuery) => $eventQuery->where('title', 'like', $term));
                });
            })
            ->when($this->statusFilter !== '', fn (Builder $query) => $query->whereHas(
                'event',
                fn (Builder $eventQuery) => $eventQuery->where('status', $this->statusFilter),
            ))
            ->when($this->typeFilter !== '', function (Builder $query): void {
                $extensions = match ($this->typeFilter) {
                    'image' => ['jpg', 'jpeg', 'png', 'webp', 'gif'],
                    'video' => ['mp4', 'webm', 'mov', 'm4v'],
                    'pdf' => ['pdf'],
                    'office' => ['doc', 'docx', 'xls', 'xlsx'],
                    default => [],
                };

                if ($extensions === []) {
                    return;
                }

                $query->where(function (Builder $query) use ($extensions): void {
                    foreach ($extensions as $extension) {
                        $query->orWhere('file_path', 'like', '%.' . $extension);
                    }
                });
            })
            ->latest('created_at')
            ->paginate(10);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedTypeFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'typeFilter']);
        $this->resetPage();
    }
}
