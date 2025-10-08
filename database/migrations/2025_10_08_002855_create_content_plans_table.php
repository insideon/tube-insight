<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('topic')->nullable(); // 소재
            $table->text('concept')->nullable(); // 기획 의도
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('expected_competitiveness_score')->nullable(); // 예상 소재 경쟁력 점수 (1-10)
            $table->unsignedTinyInteger('qc_score')->nullable(); // QC 평가 점수 (1-10)
            $table->text('qc_feedback')->nullable(); // QC 피드백
            $table->foreignId('qc_reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('qc_reviewed_at')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, qc_review, approved, rejected, published
            $table->string('youtube_video_id')->nullable(); // 실제 게시된 영상 ID
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('creator_id');
            $table->index('channel_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_plans');
    }
};
