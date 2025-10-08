<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 관리자 계정 생성
        User::create([
            'name' => 'Admin',
            'email' => 'admin@tube-insight.com',
            'password' => Hash::make('Admin@1234!'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('========================================');
        $this->command->info('✅ 관리자 계정이 생성되었습니다!');
        $this->command->info('========================================');
        $this->command->info('📧 이메일: admin@tube-insight.com');
        $this->command->info('🔑 비밀번호: Admin@1234!');
        $this->command->info('⚠️  프로덕션에서는 반드시 비밀번호를 변경하세요!');
        $this->command->info('========================================');
    }
}
