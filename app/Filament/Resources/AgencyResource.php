<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgencyResource\Pages;
use App\Models\Agency;
use App\Models\AgencyType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AgencyResource extends Resource
{
    protected static ?string $model = Agency::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $modelLabel = 'Instansi';

    public static function getNavigationGroup(): ?string { return null; }
    public static function getNavigationSort(): ?int { return 4; }
    public static function getNavigationLabel(): string { return 'Instansi'; }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Nama instansi')->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('type')
                    ->label('Jenis')
                    ->options(fn () => AgencyType::query()->where('status', 'active')->orderBy('name')->pluck('name', 'name'))
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama jenis instansi')
                            ->required()
                            ->maxLength(100)
                            ->unique('agency_types', 'name'),
                    ])
                    ->createOptionUsing(fn (array $data): string => AgencyType::create([
                        'name' => $data['name'],
                        'status' => 'active',
                    ])->name)
                    ->required(),
                Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('phone')->label('Telepon'),
                Forms\Components\FileUpload::make('logo')->label('Logo instansi')->image()->disk('public')->directory('agencies/logos'),
                Forms\Components\Textarea::make('address')->label('Alamat')->columnSpanFull(),
                Forms\Components\Select::make('province_code')->label('Provinsi')->options(fn () => collect(app(\App\Services\IndonesiaRegionService::class)->get('provinces'))->pluck('name', 'id'))->searchable()->live()->extraFieldWrapperAttributes(['class' => 'region-select-field', 'data-region-level' => 'province'])->extraInputAttributes(['x-on:change' => "window.startRegionLoading(\$el, 'province')"])->afterStateUpdated(fn (Forms\Set $set) => [$set('regency_code', null), $set('district_code', null), $set('village_code', null)]),
                Forms\Components\Select::make('regency_code')->label('Kabupaten / kota')->options(fn (Forms\Get $get) => $get('province_code') ? collect(app(\App\Services\IndonesiaRegionService::class)->get('regencies', $get('province_code')))->pluck('name', 'id') : [])->searchable()->live()->disabled(fn (Forms\Get $get) => ! $get('province_code'))->extraFieldWrapperAttributes(['class' => 'region-select-field', 'data-region-level' => 'regency'])->extraInputAttributes(['x-on:change' => "window.startRegionLoading(\$el, 'regency')"])->afterStateUpdated(fn (Forms\Set $set) => [$set('district_code', null), $set('village_code', null)]),
                Forms\Components\Select::make('district_code')->label('Kecamatan')->options(fn (Forms\Get $get) => $get('regency_code') ? collect(app(\App\Services\IndonesiaRegionService::class)->get('districts', $get('regency_code')))->pluck('name', 'id') : [])->searchable()->live()->disabled(fn (Forms\Get $get) => ! $get('regency_code'))->extraFieldWrapperAttributes(['class' => 'region-select-field', 'data-region-level' => 'district'])->extraInputAttributes(['x-on:change' => "window.startRegionLoading(\$el, 'district')"])->afterStateUpdated(fn (Forms\Set $set) => $set('village_code', null)),
                Forms\Components\Select::make('village_code')->label('Kelurahan / desa')->options(fn (Forms\Get $get) => $get('district_code') ? collect(app(\App\Services\IndonesiaRegionService::class)->get('villages', $get('district_code')))->pluck('name', 'id') : [])->searchable()->disabled(fn (Forms\Get $get) => ! $get('district_code'))->extraFieldWrapperAttributes(['class' => 'region-select-field', 'data-region-level' => 'village']),
                Forms\Components\Select::make('status')->options(['active'=>'Aktif','inactive'=>'Nonaktif'])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('row_index')->label('No')->rowIndex(),
                Tables\Columns\ImageColumn::make('logo')->label('Logo')->disk('public')->square()->size(38),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Instansi')
                    ->searchable()
                    ->wrap()
                    ->extraHeaderAttributes(['class' => 'agency-name-column'])
                    ->extraCellAttributes(['class' => 'agency-name-column']),
                Tables\Columns\TextColumn::make('type')->label('Jenis Instansi')->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->extraHeaderAttributes(['class' => 'agency-email-column'])
                    ->extraCellAttributes(['class' => 'agency-email-column']),
                Tables\Columns\TextColumn::make('phone')->label('No. Telepon')->placeholder('-'),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'active' ? 'Aktif' : 'Nonaktif')
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'gray'),
            ])
            ->actionsColumnLabel('Aksi')
            ->actionsAlignment('start')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat')->icon('heroicon-o-eye'),
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->disabled(fn (Agency $record): bool => $record->events()->exists())
                    ->tooltip(fn (Agency $record): ?string => $record->events()->exists()
                        ? 'Instansi masih memiliki festival atau event. Nonaktifkan instansi jika tidak ingin digunakan lagi.'
                        : null),
            ])
            ->paginated([6, 12, 24])
            ->defaultPaginationPageOption(6)
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgencies::route('/'),
            'create' => Pages\CreateAgency::route('/create'),
            'edit' => Pages\EditAgency::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool { return auth()->user()?->role === 'super_admin'; }
}
