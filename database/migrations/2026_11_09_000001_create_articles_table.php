<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $t) {
            $t->id();
            $t->foreignId('poli_id')->constrained('polis')->cascadeOnDelete();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('summary', 300);
            $t->longText('body');
            $t->string('category', 40)->default('Edukasi');
            $t->string('tags')->nullable();          // "Gigi,Mulut"
            $t->string('week_key', 10)->index();     // "2026-W46"
            $t->string('source', 10)->default('gemini'); // gemini | seed
            $t->timestamp('published_at')->useCurrent();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
