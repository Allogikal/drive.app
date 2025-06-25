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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->time('worktime_start');
            $table->time('worktime_end');
            $table->string('phone');
            $table->string('restaurant_site_url')->nullable();
            $table->string('address');
            $table->decimal('average_price', 8, 2);
            $table->string('restaurant_mail');
            $table->string('INN');
            $table->string('KPP')->nullable();
            $table->string('OGRN');
            $table->string('telegram_url')->nullable();
            $table->string('whatsapp_url')->nullable();
            $table->string('vk_url')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->float('rating', 8, 2)->default(0);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
