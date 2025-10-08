<?php

namespace App\Filament\Widgets;

use App\Models\Channel;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TeamPerformanceChart extends ChartWidget
{
    protected ?string $heading = '팀별 조회수 비교';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $teams = Channel::with(['team', 'metrics' => function ($query) {
            $query->where('metric_date', '>=', Carbon::now()->subMonth())
                ->latest('metric_date')
                ->limit(1);
        }])
            ->whereNotNull('team_id')
            ->get()
            ->groupBy('team_id');

        $labels = [];
        $data = [];

        foreach ($teams as $teamId => $channels) {
            $teamName = $channels->first()->team?->name ?? '미분류';
            $totalViews = $channels->sum(function ($channel) {
                return $channel->metrics->sum('view_count');
            });

            $labels[] = $teamName;
            $data[] = $totalViews;
        }

        return [
            'datasets' => [
                [
                    'label' => '조회수',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(96, 165, 250, 0.85)', // sky blue
                        'rgba(52, 211, 153, 0.85)', // emerald
                        'rgba(251, 146, 60, 0.85)', // orange
                        'rgba(192, 132, 252, 0.85)', // purple
                        'rgba(244, 114, 182, 0.85)', // pink
                    ],
                    'borderColor' => [
                        'rgb(96, 165, 250)',
                        'rgb(52, 211, 153)',
                        'rgb(251, 146, 60)',
                        'rgb(192, 132, 252)',
                        'rgb(244, 114, 182)',
                    ],
                    'borderWidth' => 2,
                    'borderRadius' => 8,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
