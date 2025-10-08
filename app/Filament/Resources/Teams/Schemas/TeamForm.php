<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('팀명')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('설명')
                    ->rows(3)
                    ->columnSpanFull(),
                Select::make('manager_id')
                    ->label('팀장')
                    ->relationship('manager', 'name')
                    ->searchable()
                    ->preload(),
                Toggle::make('is_active')
                    ->label('활성')
                    ->default(true),
            ]);
    }
}
