<?php

namespace App\Filament\Resources\Teams\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TeamsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('팀명')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('설명')
                    ->limit(50)
                    ->toggleable(),
                TextColumn::make('manager.name')
                    ->label('팀장')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('members_count')
                    ->label('팀원 수')
                    ->counts('members')
                    ->suffix('명')
                    ->alignCenter(),
                IconColumn::make('is_active')
                    ->label('활성')
                    ->boolean()
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
                //
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
