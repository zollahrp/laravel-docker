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
        Schema::create('web_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('logo'); 
            $table->string('site_name');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->string('facebook');
            $table->string('instagram');
            $table->string('twitter');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_profiles');
    }
};
