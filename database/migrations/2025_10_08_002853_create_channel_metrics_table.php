<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channel_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained()->cascadeOnDelete();
            $table->date('metric_date');
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('subscriber_count')->default(0);
            $table->unsignedInteger('video_count')->default(0);
            $table->unsignedBigInteger('total_views')->default(0);
            $table->string('performance_grade')->nullable(); // '저성과', '중성과', '고성과'
            $table->timestamps();

            $table->unique(['channel_id', 'metric_date']);
            $table->index('metric_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channel_metrics');
    }
};
