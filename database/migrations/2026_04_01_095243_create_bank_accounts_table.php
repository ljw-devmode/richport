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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id('idx');
            $table->string('bank_name', 255);        // 은행명
            $table->string('account_number', 255);   // 계좌번호
            $table->string('account_holder', 255);   // 예금주
            $table->string('created_by', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
