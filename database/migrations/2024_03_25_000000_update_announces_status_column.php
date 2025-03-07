<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\AnnounceStatus;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('announces', function (Blueprint $table) {
            // Добавляем колонку status, если её нет
            if (!Schema::hasColumn('announces', 'status')) {
                $table->string('status')->nullable();
            }
        });

        // Обновляем существующие записи, установив значение по умолчанию для null
        DB::table('announces')
            ->whereNull('status')
            ->update(['status' => AnnounceStatus::DRAFT->value]);

        // Делаем колонку обязательной после обновления данных
        Schema::table('announces', function (Blueprint $table) {
            $table->string('status')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('announces', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
