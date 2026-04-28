<?php

namespace App\Filament\Widgets;

use App\Models\Tiket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class UserTicketStatus extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        if (Auth::user()?->hasRole('admin')) {
            return [];
        }

        $userId = Auth::id();
        
        $total = Tiket::where('user_id', $userId)->count();
        $open = Tiket::where('user_id', $userId)->where('status', 'Open')->count();
        $inProgress = Tiket::where('user_id', $userId)->where('status', 'In Progress')->count();
        $resolved = Tiket::where('user_id', $userId)->where('status', 'Resolved')->count();

        return [
            Stat::make('Total Tiket', $total)
                ->description('Semua tiket Anda')
                ->descriptionIcon('heroicon-o-ticket')
                ->color('primary'),
            Stat::make('Tiket Open', $open)
                ->description('Menunggu diproses')
                ->descriptionIcon('heroicon-o-clock')
                ->color('gray'),
            Stat::make('Sedang Diproses', $inProgress)
                ->description('Sedang ditangani IT')
                ->descriptionIcon('heroicon-o-arrow-path')
                ->color('indigo'),
            Stat::make('Tiket Selesai', $resolved)
                ->description('Silakan beri rating')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }

    public static function canView(): bool
    {
        return !Auth::user()?->hasRole('admin') ?? false;
    }
}
