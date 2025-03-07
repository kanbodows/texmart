<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_filters', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Название фильтра');
            $table->string('type')->comment('Тип фильтра (select, input, checkbox и т.д.)');
            $table->string('key')->unique()->comment('Ключ для идентификации фильтра');
            $table->json('options')->nullable()->comment('Опции для select/checkbox/radio');
            $table->boolean('is_active')->default(true)->comment('Активность фильтра');
            $table->integer('sort_order')->default(0)->comment('Порядок сортировки');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_filters');
    }
};
