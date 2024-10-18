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
        Schema::create('spirit_transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->string('fetched_endpoint');
            $table->string('fetched_endpoint_url');
            $table->json('settings');
            $table->string('fetch_result');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spirit_transaction_logs');
    }
};
