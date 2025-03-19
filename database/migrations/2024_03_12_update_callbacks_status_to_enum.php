<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\CallbackStatus;
use App\Models\Callback;

return new class extends Migration
{
    public function up()
    {
        // Сначала изменим тип колонки на string
        Schema::table('callbacks', function (Blueprint $table) {
            $table->string('status')->change();
        });

        // Обновим существующие записи
        Callback::where('status', 1)->update(['status' => CallbackStatus::COMPLETED->value]);
        Callback::where('status', 0)->update(['status' => CallbackStatus::NEW->value]);
    }

    public function down()
    {
        // Обратное преобразование
        Callback::where('status', CallbackStatus::COMPLETED->value)->update(['status' => 1]);
        Callback::where('status', CallbackStatus::NEW->value)->update(['status' => 0]);

        Schema::table('callbacks', function (Blueprint $table) {
            $table->boolean('status')->change();
        });
    }
};
