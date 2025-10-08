<?php

namespace App\Filament\Widgets;

use App\Models\ChannelMetric;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ChannelPerformanceChart extends ChartWidget
{
    protected ?string $heading = '월별 조회수 추이';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $metrics = ChannelMetric::query()
            ->where('metric_date', '>=', Carbon::now()->subMonths(12))
            ->orderBy('metric_date')
            ->get()
            ->groupBy(fn ($metric) => $metric->metric_date->format('Y-m'))
            ->map(fn ($group) => $group->sum('view_count'));

        return [
            'datasets' => [
                [
                    'label' => '조회수',
                    'data' => $metrics->values()->toArray(),
                    'borderColor' => 'rgb(96, 165, 250)',
                    'backgroundColor' => 'rgba(96, 165, 250, 0.15)',
                    'fill' => true,
                    'tension' => 0.4,
                    'borderWidth' => 3,
                    'pointRadius' => 4,
                    'pointBackgroundColor' => 'rgb(96, 165, 250)',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointHoverRadius' => 6,
                ],
            ],
            'labels' => $metrics->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return value.toLocaleString() + " 회"; }',
                    ],
                ],
            ],
        ];
    }
}
