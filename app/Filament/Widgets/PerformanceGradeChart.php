<?php

namespace App\Filament\Widgets;

use App\Models\ChannelMetric;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PerformanceGradeChart extends ChartWidget
{
    protected ?string $heading = '성과 등급별 채널 분포';

    protected static ?int $sort = 6;

    protected function getData(): array
    {
        $lastMonth = Carbon::now()->subMonth();

        $grades = ChannelMetric::whereYear('metric_date', $lastMonth->year)
            ->whereMonth('metric_date', $lastMonth->month)
            ->selectRaw('performance_grade, COUNT(DISTINCT channel_id) as count')
            ->groupBy('performance_grade')
            ->pluck('count', 'performance_grade');

        return [
            'datasets' => [
                [
                    'label' => '채널 수',
                    'data' => [
                        $grades['고성과'] ?? 0,
                        $grades['중성과'] ?? 0,
                        $grades['저성과'] ?? 0,
                    ],
                    'backgroundColor' => [
                        '#34D399', // 고성과 - emerald
                        '#FCD34D', // 중성과 - amber
                        '#FB923C', // 저성과 - orange
                    ],
                    'borderWidth' => 0,
                    'hoverOffset' => 8,
                ],
            ],
            'labels' => ['고성과', '중성과', '저성과'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 15,
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                        'font' => [
                            'size' => 12,
                            'weight' => '600',
                        ],
                    ],
                ],
            ],
        ];
    }
}
