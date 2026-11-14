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
        Schema::table('check_result', function (Blueprint $table) {
            // item_idx 컬럼 타입을 DATETIME에서 BIGINT UNSIGNED로 변경
            $table->bigInteger('item_idx')->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('check_result', function (Blueprint $table) {
            // 롤백 시 다시 DATETIME으로 변경 (원래 타입이 DATETIME이었다고 가정)
            $table->dateTime('item_idx')->nullable()->change();
        });
    }
};
