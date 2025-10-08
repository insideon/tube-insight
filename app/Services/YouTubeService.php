<?php

namespace App\Services;

use App\Models\Channel;
use App\Models\ChannelMetric;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YouTubeService
{
    protected string $apiKey;

    protected string $baseUrl = 'https://www.googleapis.com/youtube/v3';

    public function __construct()
    {
        $this->apiKey = config('services.youtube.api_key');
    }

    public function syncChannel(Channel $channel): bool
    {
        try {
            $data = $this->getChannelStatistics($channel->youtube_channel_id);

            if (! $data) {
                return false;
            }

            // 채널 정보 업데이트
            $channel->update([
                'name' => $data['snippet']['title'] ?? $channel->name,
                'description' => $data['snippet']['description'] ?? $channel->description,
                'thumbnail_url' => $data['snippet']['thumbnails']['default']['url'] ?? $channel->thumbnail_url,
                'last_synced_at' => now(),
            ]);

            // 채널 지표 저장
            ChannelMetric::updateOrCreate(
                [
                    'channel_id' => $channel->id,
                    'metric_date' => Carbon::today(),
                ],
                [
                    'view_count' => $data['statistics']['viewCount'] ?? 0,
                    'subscriber_count' => $data['statistics']['subscriberCount'] ?? 0,
                    'video_count' => $data['statistics']['videoCount'] ?? 0,
                    'total_views' => $data['statistics']['viewCount'] ?? 0,
                ]
            );

            return true;
        } catch (\Exception $e) {
            Log::error('YouTube API Sync Error: '.$e->getMessage());

            return false;
        }
    }

    protected function getChannelStatistics(string $channelId): ?array
    {
        $response = Http::get("{$this->baseUrl}/channels", [
            'key' => $this->apiKey,
            'id' => $channelId,
            'part' => 'snippet,statistics',
        ]);

        if ($response->successful()) {
            $data = $response->json();

            return $data['items'][0] ?? null;
        }

        return null;
    }

    public function syncAllChannels(): void
    {
        $channels = Channel::where('is_active', true)->get();

        foreach ($channels as $channel) {
            $this->syncChannel($channel);
        }
    }
}
