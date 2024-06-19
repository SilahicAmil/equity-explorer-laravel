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
        Schema::create('stock_data', function (Blueprint $table) {
            $table->id();
            $table->string('stock_symbol', 10);
            $table->string('stock_name', 100);
            $table->string('stock_sector', 50);
            $table->bigInteger('stock_market_cap');
            $table->decimal('current_price', 15, 2);
            $table->bigInteger('volume_traded');
            $table->decimal('high_price', 15, 2);
            $table->decimal('low_price', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_data');
    }
};
