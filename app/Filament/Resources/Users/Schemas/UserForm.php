<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('이름')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('이메일')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->label('비밀번호')
                    ->password()
                    ->required()
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->revealable(),
                Select::make('team_id')
                    ->label('소속 팀')
                    ->relationship('team', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('role')
                    ->label('역할')
                    ->options([
                        'admin' => '관리자',
                        'team_manager' => '팀장',
                        'creator' => '기획자',
                        'qc_team' => 'QC팀',
                    ])
                    ->default('creator')
                    ->required(),
                Toggle::make('is_active')
                    ->label('활성')
                    ->default(true),
                DateTimePicker::make('email_verified_at')
                    ->label('이메일 인증일')
                    ->displayFormat('Y-m-d H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
