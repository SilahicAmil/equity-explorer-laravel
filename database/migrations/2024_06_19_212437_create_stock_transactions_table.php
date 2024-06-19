<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('stock_name');
            $table->decimal('bought_price', 10, 2);
            $table->integer('num_stock_traded');
            $table->decimal('transaction_total', 10, 2);
            $table->boolean('buy')->default(false);
            $table->boolean('sell')->default(false);
            $table->timestamp('timestamp')->useCurrent();
            $table->timestamps(); // This adds `created_at` and `updated_at` columns automatically
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_transactions');
    }
}
