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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->index('audits_user_id_foreign');
            $table->foreignId('transaction_id')->constrained('transactions')->index('audits_transaction_id_foreign');
            $table->foreignId('wallet_id')->constrained('wallets')->index('audits_wallet_id_foreign');
            $table->string("description");
            $table->string("type");
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
