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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo_url');
            $table->text('description')->nullable();
            $table->string('added_url')->nullable();
            $table->string('location_url')->nullable();
            $table->string('meta_pixel_id')->nullable();
            $table->string('tiktok_pixel_id')->nullable();
            $table->string('google_tag_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
