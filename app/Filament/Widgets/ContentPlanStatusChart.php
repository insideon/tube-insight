<?php

namespace App\Filament\Widgets;

use App\Models\ContentPlan;
use Filament\Widgets\ChartWidget;

class ContentPlanStatusChart extends ChartWidget
{
    protected ?string $heading = '콘텐츠 기획안 상태';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $statuses = ContentPlan::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $statusLabels = [
            'draft' => '작성중',
            'submitted' => '제출됨',
            'qc_review' => 'QC 검토중',
            'approved' => '승인됨',
            'rejected' => '반려됨',
            'published' => '게시됨',
        ];

        $labels = [];
        $data = [];

        foreach ($statuses as $status => $count) {
            $labels[] = $statusLabels[$status] ?? $status;
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => '기획안 수',
                    'data' => $data,
                    'backgroundColor' => [
                        '#94A3B8', // 작성중 - slate
                        '#60A5FA', // 제출됨 - sky blue
                        '#FCD34D', // QC 검토중 - amber
                        '#34D399', // 승인됨 - emerald
                        '#F87171', // 반려됨 - rose
                        '#C084FC', // 게시됨 - purple
                    ],
                    'borderWidth' => 0,
                    'hoverOffset' => 8,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
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
                        ],
                    ],
                ],
            ],
            'cutout' => '65%',
        ];
    }
}
