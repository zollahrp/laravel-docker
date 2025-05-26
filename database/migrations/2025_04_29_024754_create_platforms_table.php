<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('platforms', function (Blueprint $table) {
            $table->id('platform_id');
            $table->string('name');
            $table->text('encrypted_api_key');
            $table->text('encrypted_api_secret');
            $table->string('webhook_url')->nullable();
            $table->text('refresh_token')->nullable();
            $table->dateTime('token_expiry_date')->nullable();
            $table->string('shop_name')->nullable();
            $table->string('shop_id')->nullable();
            $table->string('logo_url')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platforms');
    }
};
