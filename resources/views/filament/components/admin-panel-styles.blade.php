@if (filament()->auth()->check())
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}?v={{ filemtime(public_path('css/admin-panel.css')) }}">
@endif
