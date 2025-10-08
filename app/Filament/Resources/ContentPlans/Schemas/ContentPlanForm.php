<?php

namespace App\Filament\Resources\ContentPlans\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContentPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('제목')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Select::make('channel_id')
                    ->label('채널')
                    ->relationship('channel', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('creator_id')
                    ->label('기획자')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Textarea::make('topic')
                    ->label('소재')
                    ->rows(2)
                    ->columnSpanFull(),
                Textarea::make('concept')
                    ->label('기획 의도')
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('상세 설명')
                    ->rows(4)
                    ->columnSpanFull(),
                TextInput::make('expected_competitiveness_score')
                    ->label('예상 경쟁력 점수')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(10)
                    ->suffix('점'),
                Select::make('status')
                    ->label('상태')
                    ->options([
                        'draft' => '작성중',
                        'submitted' => '제출됨',
                        'qc_review' => 'QC 검토중',
                        'approved' => '승인됨',
                        'rejected' => '반려됨',
                        'published' => '게시됨',
                    ])
                    ->default('draft')
                    ->required(),
                TextInput::make('qc_score')
                    ->label('QC 점수')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(10)
                    ->suffix('점'),
                Textarea::make('qc_feedback')
                    ->label('QC 피드백')
                    ->rows(3)
                    ->columnSpanFull(),
                Select::make('qc_reviewer_id')
                    ->label('QC 검토자')
                    ->relationship('qcReviewer', 'name')
                    ->searchable()
                    ->preload(),
                DateTimePicker::make('qc_reviewed_at')
                    ->label('QC 검토일')
                    ->displayFormat('Y-m-d H:i'),
                TextInput::make('youtube_video_id')
                    ->label('YouTube 영상 ID')
                    ->maxLength(255),
                DateTimePicker::make('published_at')
                    ->label('게시일')
                    ->displayFormat('Y-m-d H:i'),
            ]);
    }
}
