<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anpr_logs', function (Blueprint $table) {
            $table->id();
            $table->string('method');
            $table->string('endpoint');
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->json('params')->nullable();
            $table->json('response')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamps();

            $table->index('method');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anpr_logs');
    }
};
