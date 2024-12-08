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
        Schema::create('lampiran', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('no_spp');
            $table->date('tanggal_spp');
            $table->string('unit');
            $table->decimal('hutang');
            $table->string('status_karyawan');
            $table->foreign('code')->references('code')->on('t_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lampiran');
    }
};
