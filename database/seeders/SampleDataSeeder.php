<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\ChannelMetric;
use App\Models\ContentPlan;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. 사용자 생성
        $admin = User::where('email', 'test@example.com')->first();

        // 팀장 5명 생성
        $teamManagers = [];
        $teamManagerNames = ['김팀장', '박팀장', '이팀장', '최팀장', '정팀장'];
        foreach ($teamManagerNames as $index => $name) {
            $teamManagers[] = User::create([
                'name' => $name,
                'email' => 'team'.($index + 1).'@example.com',
                'password' => Hash::make('password'),
                'role' => 'team_manager',
                'is_active' => true,
            ]);
        }

        // QC팀 3명 생성
        $qcReviewers = [];
        $qcNames = ['이QC', '강QC', '송QC'];
        foreach ($qcNames as $index => $name) {
            $qcReviewers[] = User::create([
                'name' => $name,
                'email' => 'qc'.($index + 1).'@example.com',
                'password' => Hash::make('password'),
                'role' => 'qc_team',
                'is_active' => true,
            ]);
        }

        // 2. 팀 5개 생성
        $teams = [];
        $teamData = [
            ['name' => '엔터테인먼트팀', 'description' => '예능 및 엔터테인먼트 콘텐츠 제작'],
            ['name' => '교육팀', 'description' => '교육 및 정보 콘텐츠 제작'],
            ['name' => '게임팀', 'description' => '게임 리뷰 및 플레이 콘텐츠'],
            ['name' => '뷰티&라이프팀', 'description' => '뷰티, 패션, 라이프스타일 콘텐츠'],
            ['name' => '여행&푸드팀', 'description' => '여행, 먹방, 요리 콘텐츠'],
        ];

        foreach ($teamData as $index => $data) {
            $teams[] = Team::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'manager_id' => $teamManagers[$index]->id,
                'is_active' => true,
            ]);
        }

        // 3. 기획자 12명 생성 (각 팀에 2-3명씩)
        $creators = [];
        $creatorNames = [
            '최기획', '정콘텐츠', '강크리에이터', '송제작', '한영상', '오PD',
            '서기획', '윤콘텐츠', '임크리에이터', '신제작', '권영상', '조PD',
        ];

        foreach ($creatorNames as $index => $name) {
            $team = $teams[$index % 5];
            $creator = User::create([
                'name' => $name,
                'email' => 'creator'.($index + 1).'@example.com',
                'password' => Hash::make('password'),
                'role' => 'creator',
                'team_id' => $team->id,
                'is_active' => true,
            ]);
            $creators[] = $creator;
        }

        // 팀장들도 팀에 할당
        foreach ($teamManagers as $index => $manager) {
            $manager->update(['team_id' => $teams[$index]->id]);
        }

        // 4. 채널 15개 생성
        $channelData = [
            ['name' => '재미있는 예능채널', 'description' => '웃음과 재미를 선사하는 예능', 'views' => 1500000],
            ['name' => '코딩 배우기', 'description' => '초보자를 위한 프로그래밍 강의', 'views' => 950000],
            ['name' => '게임 리뷰 전문', 'description' => '신작 게임 리뷰와 공략', 'views' => 820000],
            ['name' => '일상 Vlog', 'description' => '일상을 담은 브이로그', 'views' => 680000],
            ['name' => '요리 레시피', 'description' => '쉽고 맛있는 요리', 'views' => 550000],
            ['name' => '뷰티 메이크업', 'description' => '메이크업 튜토리얼', 'views' => 1200000],
            ['name' => 'IT 리뷰', 'description' => '최신 IT 제품 리뷰', 'views' => 780000],
            ['name' => '여행 브이로그', 'description' => '세계 여행 브이로그', 'views' => 920000],
            ['name' => '피트니스', 'description' => '홈트레이닝 루틴', 'views' => 650000],
            ['name' => '먹방', 'description' => '맛집 탐방과 먹방', 'views' => 1100000],
            ['name' => '패션 스타일링', 'description' => '데일리 룩 추천', 'views' => 580000],
            ['name' => 'e스포츠 하이라이트', 'description' => 'e스포츠 경기 하이라이트', 'views' => 890000],
            ['name' => '영어 회화', 'description' => '실용 영어 회화', 'views' => 720000],
            ['name' => '반려동물', 'description' => '귀여운 반려동물 일상', 'views' => 1050000],
            ['name' => 'DIY 인테리어', 'description' => '셀프 인테리어 꿀팁', 'views' => 480000],
        ];

        $channelModels = [];
        foreach ($channelData as $index => $data) {
            $team = $teams[$index % 5];
            $creator = $creators[$index % count($creators)];

            $channelModels[] = Channel::create([
                'youtube_channel_id' => 'UC_channel_'.($index + 1),
                'name' => $data['name'],
                'description' => $data['description'],
                'team_id' => $team->id,
                'manager_id' => $creator->id,
                'is_active' => true,
                'last_synced_at' => now(),
            ]);
        }

        // 5. 채널 지표 생성 (최근 13개월)
        foreach ($channelModels as $index => $channel) {
            $baseViews = $channelData[$index]['views'];
            $baseSubscribers = (int) ($baseViews / 30);

            for ($i = 12; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i)->startOfMonth();
                $variation = rand(-15, 25) / 100;
                $monthlyViews = (int) ($baseViews * (1 + $variation));

                ChannelMetric::create([
                    'channel_id' => $channel->id,
                    'metric_date' => $date,
                    'view_count' => $monthlyViews,
                    'subscriber_count' => (int) ($baseSubscribers * (1 + ($i * 0.02))),
                    'video_count' => rand(80, 250),
                    'total_views' => (int) ($baseViews * (13 - $i) * 1.2),
                    'performance_grade' => $monthlyViews >= 1000000 ? '고성과' : ($monthlyViews >= 500000 ? '중성과' : '저성과'),
                ]);
            }
        }

        // 6. 콘텐츠 기획안 30개 생성
        $topics = [
            ['제목' => '크리스마스 특집 콘텐츠', '소재' => '크리스마스 이벤트', '기획의도' => '연말 시청률 상승 기대'],
            ['제목' => 'Python 초급 강의 시리즈', '소재' => '프로그래밍 교육', '기획의도' => '초보자 유입 목표'],
            ['제목' => '신작 게임 리뷰', '소재' => '최신 게임 리뷰', '기획의도' => '게임 출시 타이밍 활용'],
            ['제목' => '하루 일과 브이로그', '소재' => '일상 공유', '기획의도' => '구독자와의 친밀감 형성'],
            ['제목' => '30분 완성 요리', '소재' => '간단한 레시피', '기획의도' => '바쁜 현대인 타겟'],
            ['제목' => '숨겨진 여행지 탐방', '소재' => '국내 여행', '기획의도' => '여행 시즌 맞춤 콘텐츠'],
            ['제목' => '최신 IT 트렌드', '소재' => '기술 리뷰', '기획의도' => '얼리어답터 타겟'],
            ['제목' => '운동 루틴 공유', '소재' => '홈트레이닝', '기획의도' => '건강 관심층 공략'],
            ['제목' => '메이크업 튜토리얼', '소재' => '데일리 메이크업', '기획의도' => '뷰티 관심층 유입'],
            ['제목' => 'LOL 하이라이트', '소재' => 'e스포츠 경기', '기획의도' => '게임 팬 타겟'],
            ['제목' => '일본 여행 브이로그', '소재' => '해외 여행', '기획의도' => '여행 시즌 공략'],
            ['제목' => '전국 맛집 탐방', '소재' => '로컬 맛집', '기획의도' => '먹방 트렌드 활용'],
            ['제목' => '스킨케어 루틴', '소재' => '피부관리', '기획의도' => '뷰티 정보 제공'],
            ['제목' => '강아지 훈련법', '소재' => '반려동물 훈련', '기획의도' => '반려인 타겟'],
            ['제목' => '원룸 인테리어', '소재' => 'DIY 인테리어', '기획의도' => '자취생 타겟'],
            ['제목' => 'AI 활용법', '소재' => 'ChatGPT 활용', '기획의도' => 'AI 트렌드 반영'],
            ['제목' => '주식 투자 기초', '소재' => '재테크', '기획의도' => '투자 관심층 공략'],
            ['제목' => '발로란트 공략', '소재' => '게임 공략', '기획의도' => '게이머 커뮤니티 형성'],
            ['제목' => '다이어트 식단', '소재' => '건강 식단', '기획의도' => '다이어트 시즌 공략'],
            ['제목' => '커피 내리기', '소재' => '홈카페', '기획의도' => '카페 문화 반영'],
            ['제목' => '패션 하울', '소재' => '계절 패션', '기획의도' => '패션 트렌드 반영'],
            ['제목' => '영어 회화 팁', '소재' => '실용 영어', '기획의도' => '어학 관심층 타겟'],
            ['제목' => '반려묘 일상', '소재' => '고양이 브이로그', '기획의도' => '집사들 타겟'],
            ['제목' => '셀프 네일아트', '소재' => '네일 디자인', '기획의도' => '뷰티 DIY 트렌드'],
            ['제목' => '주말 브런치', '소재' => '브런치 레시피', '기획의도' => '주말 요리 수요'],
            ['제목' => '제주도 핫플', '소재' => '제주도 여행', '기획의도' => '국내 여행 수요'],
            ['제목' => '오버워치2 플레이', '소재' => '게임 플레이', '기획의도' => '게임 신작 공략'],
            ['제목' => '직장인 아침 루틴', '소재' => '모닝 루틴', '기획의도' => '직장인 공감 콘텐츠'],
            ['제목' => '다이슨 에어랩 리뷰', '소재' => '뷰티 기기 리뷰', '기획의도' => '제품 리뷰 수요'],
            ['제목' => '베트남 여행 가이드', '소재' => '동남아 여행', '기획의도' => '해외 여행 수요'],
        ];

        $statuses = ['draft', 'submitted', 'qc_review', 'approved', 'rejected', 'published'];

        foreach ($topics as $index => $topic) {
            $creator = $creators[$index % count($creators)];
            $channel = $channelModels[$index % count($channelModels)];
            $status = $statuses[$index % count($statuses)];

            $expectedScore = rand(5, 10);
            $qcScore = in_array($status, ['draft', 'submitted']) ? null : rand(4, 10);

            ContentPlan::create([
                'channel_id' => $channel->id,
                'creator_id' => $creator->id,
                'title' => $topic['제목'],
                'topic' => $topic['소재'],
                'concept' => $topic['기획의도'],
                'description' => $topic['기획의도'].' 관련 상세 기획안 내용입니다.',
                'expected_competitiveness_score' => $expectedScore,
                'qc_score' => $qcScore,
                'qc_feedback' => $qcScore ? ($qcScore >= 7 ? '우수한 기획안입니다.' : '보완이 필요합니다.') : null,
                'qc_reviewer_id' => $qcScore ? $qcReviewers[rand(0, count($qcReviewers) - 1)]->id : null,
                'qc_reviewed_at' => $qcScore ? now()->subDays(rand(1, 30)) : null,
                'status' => $status,
                'youtube_video_id' => $status === 'published' ? 'VIDEO_'.($index + 1) : null,
                'published_at' => $status === 'published' ? now()->subDays(rand(1, 60)) : null,
            ]);
        }

        $this->command->info('========================================');
        $this->command->info('✅ 샘플 데이터가 성공적으로 생성되었습니다!');
        $this->command->info('========================================');
        $this->command->info('👥 팀: '.count($teams).'개');
        $this->command->info('👤 사용자: '.(count($teamManagers) + count($qcReviewers) + count($creators) + 1).'명');
        $this->command->info('   - 관리자: 1명');
        $this->command->info('   - 팀장: '.count($teamManagers).'명');
        $this->command->info('   - QC팀: '.count($qcReviewers).'명');
        $this->command->info('   - 기획자: '.count($creators).'명');
        $this->command->info('📺 채널: '.count($channelModels).'개');
        $this->command->info('📈 채널 지표: '.(count($channelModels) * 13).'개 (각 채널별 13개월)');
        $this->command->info('📝 콘텐츠 기획안: '.count($topics).'개');
        $this->command->info('========================================');
    }
}
