<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Название фильтра');
            $table->string('filter_key')->comment('Ключ фильтра');
        });
    }

    public function down(): void
    {
        // Schema::dropIfExists('filters');
    }
};
