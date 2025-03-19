<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('callbacks', function (Blueprint $table) {
            $table->string('email')->nullable()->after('phone');
            $table->text('comment')->nullable()->after('email');
        });
    }

    public function down()
    {
        Schema::table('callbacks', function (Blueprint $table) {
            $table->dropColumn(['email', 'comment']);
        });
    }
};
