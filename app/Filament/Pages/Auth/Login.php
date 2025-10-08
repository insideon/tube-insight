<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class Login extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();

        // 프로토타입 데모용 - 자동 로그인 정보 채우기
        $this->form->fill([
            'email' => 'admin@tube-insight.com',
            'password' => 'Admin@1234!',
            'remember' => true,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->label('이메일')
            ->placeholder('your@email.com');
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->label('비밀번호')
            ->placeholder('비밀번호를 입력하세요');
    }
}
