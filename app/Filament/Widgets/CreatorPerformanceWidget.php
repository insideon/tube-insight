<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CreatorPerformanceWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = '기획자별 성과';

    protected static ?int $sort = 8;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->where('role', 'creator')
                    ->withCount(['contentPlans', 'managedChannels'])
            )
            ->columns([
                TextColumn::make('name')
                    ->label('기획자')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('team.name')
                    ->label('소속 팀')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('content_plans_count')
                    ->label('작성 기획안')
                    ->sortable()
                    ->suffix(' 개')
                    ->alignCenter(),

                TextColumn::make('managed_channels_count')
                    ->label('담당 채널')
                    ->sortable()
                    ->suffix(' 개')
                    ->alignCenter(),

                TextColumn::make('contentPlans')
                    ->label('승인률')
                    ->getStateUsing(function ($record) {
                        $total = $record->contentPlans()->count();
                        $approved = $record->contentPlans()->where('status', 'approved')->count();

                        return $total > 0 ? round(($approved / $total) * 100, 1).'%' : '0%';
                    })
                    ->alignCenter()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        (float) str_replace('%', '', $state) >= 70 => 'success',
                        (float) str_replace('%', '', $state) >= 50 => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('contentPlans')
                    ->label('평균 QC 점수')
                    ->getStateUsing(function ($record) {
                        $avgScore = $record->contentPlans()
                            ->whereNotNull('qc_score')
                            ->avg('qc_score');

                        return $avgScore ? round($avgScore, 1).'점' : '-';
                    })
                    ->alignCenter(),
            ])
            ->defaultSort('content_plans_count', 'desc');
    }
}
