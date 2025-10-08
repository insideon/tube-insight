<?php

namespace App\Filament\Resources\Channels\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ChannelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('youtube_channel_id')
                    ->label('YouTube 채널 ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label('채널명')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('team.name')
                    ->label('소속 팀')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('manager.name')
                    ->label('담당자')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('활성')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('last_synced_at')
                    ->label('마지막 동기화')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('생성일')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('수정일')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('team_id')
                    ->label('팀')
                    ->relationship('team', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('manager_id')
                    ->label('담당자')
                    ->relationship('manager', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_active')
                    ->label('활성 상태')
                    ->placeholder('전체')
                    ->trueLabel('활성')
                    ->falseLabel('비활성'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
