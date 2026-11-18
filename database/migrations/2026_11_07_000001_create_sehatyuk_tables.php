<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tambahan kolom pada tabel users bawaan Laravel
        Schema::table('users', function (Blueprint $t) {
            $t->string('phone', 20)->nullable()->after('email');
            $t->string('role', 20)->default('pasien')->after('phone'); // admin | petugas | pasien
            $t->string('google_id')->nullable()->unique()->after('role');
        });

        Schema::create('polis', function (Blueprint $t) {
            $t->id();
            $t->string('code', 5)->unique();
            $t->string('name');
            $t->text('description')->nullable();
            $t->timestamps();
        });

        Schema::create('doctors', function (Blueprint $t) {
            $t->id();
            $t->foreignId('poli_id')->constrained('polis')->cascadeOnDelete();
            $t->string('name');
            $t->string('specialty');
            $t->string('days');               // "Senin,Rabu,Jumat"
            $t->time('start_time');
            $t->time('end_time');
            $t->unsignedInteger('quota')->default(15);
            $t->timestamps();
        });

        Schema::create('tickets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $t->date('visit_date');
            $t->unsignedInteger('queue_number');
            $t->string('queue_code', 12);
            $t->string('booking_code', 10);
            $t->string('referral_number', 20);
            $t->string('phone', 20);
            $t->string('status', 15)->default('waiting'); // waiting|called|examining|done|skipped
            $t->timestamp('called_at')->nullable();
            $t->timestamp('notified_at')->nullable();
            $t->timestamps();
            $t->index(['doctor_id', 'visit_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('polis');
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn(['phone', 'role', 'google_id']);
        });
    }
};
