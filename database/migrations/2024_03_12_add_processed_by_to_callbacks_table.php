<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('callbacks', function (Blueprint $table) {
            $table->foreignId('updated_by')->nullable()->after('status')
                  ->constrained('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('callbacks', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['updated_by', 'updated_at']);
        });
    }
};
