<?php

namespace App\Filament\Widgets;

use App\Models\Tiket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class AdminTicketStats extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        if (!Auth::user()?->hasRole('admin')) {
            return [];
        }

        $total = Tiket::count();
        $open = Tiket::where('status', 'Open')->count();
        $inProgress = Tiket::where('status', 'In Progress')->count();
        $resolved = Tiket::where('status', 'Resolved')->count();
        $closed = Tiket::where('status', 'Closed')->count();
        $completed = $resolved + $closed;

        return [
            Stat::make('Total Tiket', $total)
                ->description('Semua tiket masuk')
                ->descriptionIcon('heroicon-o-ticket')
                ->color('primary'),
            Stat::make('Tiket Open', $open)
                ->description('Menunggu diproses')
                ->descriptionIcon('heroicon-o-clock')
                ->color('gray'),
            Stat::make('Sedang Diproses', $inProgress)
                ->description('Sedang ditangani')
                ->descriptionIcon('heroicon-o-arrow-path')
                ->color('indigo'),
            Stat::make('Tiket Selesai', $completed)
                ->description('Resolved + Closed')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }

    public static function canView(): bool
    {
        return Auth::user()?->hasRole('admin') ?? false;
    }
}
