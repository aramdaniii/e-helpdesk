<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TiketResource\Pages;
use App\Models\Tiket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TiketResource extends Resource
{
    protected static ?string $model = Tiket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Tiket';

    public static function getEloquentQuery(): Builder
    {
        $isAdmin = Auth::user()?->hasRole('admin') ?? false;

        return parent::getEloquentQuery()
            ->when(!$isAdmin, function (Builder $query) {
                $query->where('user_id', Auth::id());
            });
    }

    public static function form(Form $form): Form
    {
        $isAdmin = Auth::user()?->hasRole('admin') ?? false;

        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(fn() => Auth::id())
                    ->required(),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('kode_tiket')
                            ->default(fn() => 'TKT-' . date('Y') . '-001')
                            ->disabled()
                            ->required(),
                        Forms\Components\TextInput::make('judul_kendala')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\Select::make('kategori')
                            ->options([
                                'Software' => 'Software',
                                'Hardware' => 'Hardware',
                                'Jaringan' => 'Jaringan',
                                'Printer' => 'Printer',
                                'Aplikasi' => 'Aplikasi',
                            ])
                            ->required(),
                        Forms\Components\Select::make('prioritas')
                            ->options([
                                'Rendah' => 'Rendah',
                                'Sedang' => 'Sedang',
                                'Tinggi' => 'Tinggi',
                            ])
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'Open' => 'Open',
                                'In Progress' => 'In Progress',
                                'Pending' => 'Pending',
                                'Resolved' => 'Resolved',
                                'Closed' => 'Closed',
                            ])
                            ->default('Open')
                            ->required()
                            ->disabled(!$isAdmin),
                        Forms\Components\FileUpload::make('foto_kendala')
                            ->image()
                            ->imageEditor()
                            ->directory('tiket-fotos')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->previewable(true)
                            ->openable()
                            ->downloadable()
                            ->columnSpan(2),
                    ]),
                Forms\Components\Textarea::make('deskripsi')
                    ->required()
                    ->columnSpanFull()
                    ->rows(4),
                Forms\Components\Textarea::make('solusi_teknis')
                    ->visible(fn() => Auth::user()?->hasRole('admin') ?? false)
                    ->columnSpanFull()
                    ->rows(4),
                Forms\Components\Textarea::make('komentar_balasan')
                    ->columnSpanFull()
                    ->rows(3),
                Forms\Components\Select::make('rating')
                    ->options([
                        1 => '1 - Sangat Buruk',
                        2 => '2 - Buruk',
                        3 => '3 - Cukup',
                        4 => '4 - Baik',
                        5 => '5 - Sangat Baik',
                    ])
                    ->visible(fn($record) => !$isAdmin && $record?->status === 'Resolved')
                    ->required(fn($record) => !$isAdmin && $record?->status === 'Resolved')
                    ->disabled(fn($record) => $record?->status === 'Closed'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make('Informasi Tiket')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('kode_tiket')
                            ->label('Kode Tiket'),
                        \Filament\Infolists\Components\TextEntry::make('judul_kendala')
                            ->label('Judul Kendala'),
                        \Filament\Infolists\Components\TextEntry::make('kategori')
                            ->label('Kategori')
                            ->badge()
                            ->color(fn(string $state): string => match($state) {
                                'Software' => 'blue',
                                'Hardware' => 'orange',
                                'Jaringan' => 'green',
                                'Printer' => 'purple',
                                'Aplikasi' => 'pink',
                            }),
                        \Filament\Infolists\Components\TextEntry::make('prioritas')
                            ->label('Prioritas')
                            ->badge()
                            ->color(fn(string $state): string => match($state) {
                                'Rendah' => 'gray',
                                'Sedang' => 'yellow',
                                'Tinggi' => 'red',
                            }),
                        \Filament\Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => match($state) {
                                'Open' => 'gray',
                                'In Progress' => 'blue',
                                'Pending' => 'yellow',
                                'Resolved' => 'green',
                                'Closed' => 'red',
                            }),
                        \Filament\Infolists\Components\TextEntry::make('deskripsi')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                        \Filament\Infolists\Components\ImageEntry::make('foto_kendala')
                            ->label('Foto Kendala')
                            ->visibility('public')
                            ->extraAttributes(['loading' => 'lazy'])
                            ->columnSpanFull(),
                        \Filament\Infolists\Components\TextEntry::make('solusi_teknis')
                            ->label('Solusi Teknis')
                            ->visible(fn() => Auth::user()?->hasRole('admin') ?? false)
                            ->columnSpanFull(),
                        \Filament\Infolists\Components\TextEntry::make('rating')
                            ->label('Rating')
                            ->visible(fn($record) => $record?->status === 'Closed')
                            ->formatStateUsing(fn($state) => $state ? $state . '/5' : '-'),
                        \Filament\Infolists\Components\TextEntry::make('komentar_balasan')
                            ->label('Komentar Balasan')
                            ->visible(fn($record) => $record?->status === 'Closed')
                            ->columnSpanFull(),
                        \Filament\Infolists\Components\TextEntry::make('user.name')
                            ->label('Dibuat Oleh')
                            ->visible(fn() => Auth::user()?->hasRole('admin') ?? false),
                        \Filament\Infolists\Components\TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime(),
                        \Filament\Infolists\Components\TextEntry::make('updated_at')
                            ->label('Diperbarui Pada')
                            ->dateTime(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        $isAdmin = Auth::user()?->hasRole('admin') ?? false;

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_tiket')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('judul_kendala')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('kategori')
                    ->badge()
                    ->color(fn(string $state): string => match($state) {
                        'Software' => 'blue',
                        'Hardware' => 'orange',
                        'Jaringan' => 'green',
                        'Printer' => 'purple',
                        'Aplikasi' => 'pink',
                    }),
                Tables\Columns\TextColumn::make('prioritas')
                    ->badge()
                    ->color(fn(string $state): string => match($state) {
                        'Rendah' => 'gray',
                        'Sedang' => 'yellow',
                        'Tinggi' => 'red',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match($state) {
                        'Open' => 'gray',
                        'In Progress' => 'indigo',
                        'Pending' => 'warning',
                        'Resolved' => 'success',
                        'Closed' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('rating')
                    ->badge()
                    ->color('success')
                    ->visible(fn($record) => $record?->rating !== null),
                Tables\Columns\TextColumn::make('user.name')
                    ->visible($isAdmin)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->options([
                        'Software' => 'Software',
                        'Hardware' => 'Hardware',
                        'Jaringan' => 'Jaringan',
                        'Printer' => 'Printer',
                        'Aplikasi' => 'Aplikasi',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Open' => 'Open',
                        'In Progress' => 'In Progress',
                        'Pending' => 'Pending',
                        'Resolved' => 'Resolved',
                        'Closed' => 'Closed',
                    ]),
            ])
            ->actions([
                \Filament\Tables\Actions\ViewAction::make(),
                \Filament\Tables\Actions\EditAction::make()
                    ->visible(fn($record) => $isAdmin || ($record->status !== 'Resolved' && $record->status !== 'Closed')),
                \Filament\Tables\Actions\DeleteAction::make()
                    ->visible($isAdmin),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make()
                        ->visible($isAdmin),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTikets::route('/'),
            'create' => Pages\CreateTiket::route('/create'),
            'edit' => Pages\EditTiket::route('/{record}/edit'),
        ];
    }
}
