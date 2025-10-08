<?php

namespace App\Filament\Widgets;

use App\Models\Channel;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class TopChannelsTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = '최근 성과 상위 채널';

    protected static ?int $sort = 7;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Channel::query()
                    ->with(['metrics' => function ($query) {
                        $query->where('metric_date', '>=', Carbon::now()->subMonth())
                            ->latest('metric_date')
                            ->limit(1);
                    }, 'team', 'manager'])
                    ->whereHas('metrics', function (Builder $query) {
                        $query->where('metric_date', '>=', Carbon::now()->subMonth());
                    })
            )
            ->columns([
                TextColumn::make('name')
                    ->label('채널명')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('team.name')
                    ->label('소속 팀')
                    ->sortable(),

                TextColumn::make('manager.name')
                    ->label('담당자')
                    ->sortable(),

                TextColumn::make('metrics')
                    ->label('최근 조회수')
                    ->getStateUsing(fn ($record) => $record->metrics->first()?->view_count ?? 0)
                    ->numeric()
                    ->sortable()
                    ->suffix(' 회'),

                TextColumn::make('metrics')
                    ->label('구독자')
                    ->getStateUsing(fn ($record) => $record->metrics->first()?->subscriber_count ?? 0)
                    ->numeric()
                    ->sortable()
                    ->suffix(' 명'),

                TextColumn::make('metrics')
                    ->label('성과 등급')
                    ->getStateUsing(fn ($record) => $record->metrics->first()?->performance_grade ?? '-')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '고성과' => 'success',
                        '중성과' => 'warning',
                        '저성과' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort(function ($query) {
                $query->orderByRaw('(
                    SELECT view_count
                    FROM channel_metrics
                    WHERE channel_metrics.channel_id = channels.id
                    AND metric_date >= ?
                    ORDER BY metric_date DESC
                    LIMIT 1
                ) DESC', [Carbon::now()->subMonth()]);
            });
    }
}
