<?php

namespace App\Filament\Widgets;

use App\Models\Tiket;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class TicketCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Kategori Kendala';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        if (!Auth::user()?->hasRole('admin')) {
            return [];
        }

        $categories = ['Software', 'Hardware', 'Jaringan', 'Printer', 'Aplikasi'];
        $data = [];

        foreach ($categories as $category) {
            $data[] = Tiket::where('kategori', $category)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Tiket',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgb(59, 130, 246)',  // blue
                        'rgb(249, 115, 22)',  // orange
                        'rgb(34, 197, 94)',   // green
                        'rgb(168, 85, 247)',  // purple
                        'rgb(236, 72, 153)',  // pink
                    ],
                ],
            ],
            'labels' => $categories,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    public static function canView(): bool
    {
        return Auth::user()?->hasRole('admin') ?? false;
    }
}
