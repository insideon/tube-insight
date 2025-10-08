<?php

namespace App\Filament\Resources\Channels\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ChannelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('youtube_channel_id')
                    ->label('YouTube 채널 ID')
                    ->required()
                    ->maxLength(255),
                TextInput::make('name')
                    ->label('채널명')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('설명')
                    ->columnSpanFull()
                    ->rows(3),
                TextInput::make('thumbnail_url')
                    ->label('썸네일 URL')
                    ->url()
                    ->maxLength(255),
                Select::make('team_id')
                    ->label('소속 팀')
                    ->relationship('team', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('manager_id')
                    ->label('담당자')
                    ->relationship('manager', 'name')
                    ->searchable()
                    ->preload(),
                Toggle::make('is_active')
                    ->label('활성')
                    ->default(true),
                DateTimePicker::make('last_synced_at')
                    ->label('마지막 동기화')
                    ->displayFormat('Y-m-d H:i'),
            ]);
    }
}
