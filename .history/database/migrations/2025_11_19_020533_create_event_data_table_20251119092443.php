<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_data', function (Blueprint $table) {
            $table->id();
            $table->string('peserta');
            $table->string('budget');
            $table->string('speaker');
            $table->string('topik');
            $table->string('hasil');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_data');
    }
};