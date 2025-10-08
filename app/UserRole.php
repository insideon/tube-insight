<?php

namespace App;

enum UserRole: string
{
    case ADMIN = 'admin';
    case TEAM_MANAGER = 'team_manager';
    case CREATOR = 'creator';
    case QC_TEAM = 'qc_team';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => '관리자',
            self::TEAM_MANAGER => '팀장',
            self::CREATOR => '기획자',
            self::QC_TEAM => 'QC팀',
        };
    }

    public static function labels(): array
    {
        return [
            self::ADMIN->value => self::ADMIN->label(),
            self::TEAM_MANAGER->value => self::TEAM_MANAGER->label(),
            self::CREATOR->value => self::CREATOR->label(),
            self::QC_TEAM->value => self::QC_TEAM->label(),
        ];
    }
}
