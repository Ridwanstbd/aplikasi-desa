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
        Schema::create('live_consults', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->text('address');
            $table->string('user_whatsapp');
            $table->string('name_kandang');
            $table->string('jenis_hewan');
            $table->text('data_pembelian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_consults');
    }
};
