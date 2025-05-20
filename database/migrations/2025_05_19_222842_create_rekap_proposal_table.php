<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rekap_proposal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->nullable()->constrained('periods')->onDelete('cascade');
            $table->foreignId('dosen_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('topik_id')->nullable()->constrained('topics')->onDelete('cascade');
            $table->foreignId('propsub_id')->nullable()->constrained('proposal_submissions')->onDelete('cascade');
            $table->unsignedInteger('kuota_saat_rekap')->nullable();
            $table->unsignedInteger('jumlah_bimbingan_saat_rekap')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_proposal');
    }
};
