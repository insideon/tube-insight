<?php

namespace App\Console\Commands;

use App\Services\YouTubeService;
use Illuminate\Console\Command;

class SyncYouTubeChannels extends Command
{
    protected $signature = 'youtube:sync';

    protected $description = 'YouTube 채널 데이터를 동기화합니다';

    public function handle(YouTubeService $youtubeService): int
    {
        $this->info('YouTube 채널 동기화를 시작합니다...');

        $youtubeService->syncAllChannels();

        $this->info('YouTube 채널 동기화가 완료되었습니다.');

        return Command::SUCCESS;
    }
}
