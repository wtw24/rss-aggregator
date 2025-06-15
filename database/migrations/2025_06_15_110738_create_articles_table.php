<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', static function (Blueprint $table): void {
            $table->ulid('id')->primary();

            $table->string('title');
            $table->text('summary');
            $table->string('link');

            $table
                ->foreignUlid('feed_id')
                ->index()
                ->constrained('feeds')
                ->cascadeOnDelete();

            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (! app()->isProduction()) {
            Schema::dropIfExists('articles');
        }
    }
};
