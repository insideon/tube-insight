<?php

namespace App\Console\Commands;

use App\Models\ChannelMetric;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CalculatePerformanceGrade extends Command
{
    protected $signature = 'performance:calculate-grade';

    protected $description = '채널 성과 등급을 자동으로 계산하여 업데이트합니다';

    public function handle(): int
    {
        $this->info('성과 등급 계산을 시작합니다...');

        $lastMonth = Carbon::now()->subMonth();
        $metrics = ChannelMetric::whereYear('metric_date', $lastMonth->year)
            ->whereMonth('metric_date', $lastMonth->month)
            ->get();

        $updated = 0;

        foreach ($metrics as $metric) {
            $grade = ChannelMetric::calculatePerformanceGrade($metric->view_count);

            if ($metric->performance_grade !== $grade) {
                $metric->update(['performance_grade' => $grade]);
                $updated++;
            }
        }

        $this->info("총 {$updated}개의 채널 성과 등급이 업데이트되었습니다.");

        return Command::SUCCESS;
    }
}
