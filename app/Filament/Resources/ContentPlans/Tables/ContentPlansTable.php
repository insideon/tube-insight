<?php

namespace App\Filament\Resources\ContentPlans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContentPlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('제목')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('channel.name')
                    ->label('채널')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('creator.name')
                    ->label('기획자')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('expected_competitiveness_score')
                    ->label('예상 점수')
                    ->numeric()
                    ->sortable()
                    ->suffix('점')
                    ->alignCenter(),
                TextColumn::make('qc_score')
                    ->label('QC 점수')
                    ->numeric()
                    ->sortable()
                    ->suffix('점')
                    ->alignCenter(),
                TextColumn::make('qcReviewer.name')
                    ->label('QC 검토자')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('qc_reviewed_at')
                    ->label('QC 검토일')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('상태')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'submitted' => 'info',
                        'qc_review' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'published' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => '작성중',
                        'submitted' => '제출됨',
                        'qc_review' => 'QC 검토중',
                        'approved' => '승인됨',
                        'rejected' => '반려됨',
                        'published' => '게시됨',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('게시일')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                SelectFilter::make('status')
                    ->label('상태')
                    ->options([
                        'draft' => '작성중',
                        'submitted' => '제출됨',
                        'qc_review' => 'QC 검토중',
                        'approved' => '승인됨',
                        'rejected' => '반려됨',
                        'published' => '게시됨',
                    ]),

                SelectFilter::make('channel_id')
                    ->label('채널')
                    ->relationship('channel', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('creator_id')
                    ->label('기획자')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('qc_reviewer_id')
                    ->label('QC 검토자')
                    ->relationship('qcReviewer', 'name')
                    ->searchable()
                    ->preload(),
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
