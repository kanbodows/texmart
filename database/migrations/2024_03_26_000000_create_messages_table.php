<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('to_user_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            // Индексы для быстрого поиска сообщений
            $table->index(['from_user_id', 'to_user_id']);
            $table->index(['to_user_id', 'from_user_id']);
            $table->index('is_read');
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
