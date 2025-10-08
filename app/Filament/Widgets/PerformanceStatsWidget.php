<?php

namespace App\Filament\Widgets;

use App\Models\Channel;
use App\Models\ContentPlan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PerformanceStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalChannels = Channel::where('is_active', true)->count();
        $totalContentPlans = ContentPlan::count();
        $pendingQc = ContentPlan::where('status', 'qc_review')->count();

        return [
            Stat::make('활성 채널', number_format($totalChannels))
                ->description('전체 관리 중인 채널')
                ->descriptionIcon('heroicon-m-tv')
                ->color('success')
                ->chart([7, 4, 8, 5, 9, 6, 10]),

            Stat::make('콘텐츠 기획안', number_format($totalContentPlans))
                ->description('전체 기획안')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info')
                ->chart([3, 5, 4, 7, 6, 8, 9]),

            Stat::make('QC 대기', number_format($pendingQc))
                ->description('검토 대기 중')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->chart([2, 3, 2, 4, 3, 2, $pendingQc]),
        ];
    }
}
