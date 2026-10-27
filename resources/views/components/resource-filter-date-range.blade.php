@props([
    'from',
    'until',
    'fromLabel' => 'Tanggal mulai',
    'untilLabel' => 'Tanggal akhir',
])

<div class="resource-filter-date-fields">
    <input type="date" aria-label="{{ $fromLabel }}" wire:model.live="{{ $from }}" onclick="this.showPicker?.()">
    <span>-</span>
    <input type="date" aria-label="{{ $untilLabel }}" wire:model.live="{{ $until }}" onclick="this.showPicker?.()">
</div>
