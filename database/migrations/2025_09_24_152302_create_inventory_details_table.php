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
        Schema::create('inventory_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventories');
            $table->foreignId('product_id')->constrained('products');
            $table->decimal('starting_amount', 10, 2);
            $table->decimal('ending_amount', 10, 2);
            $table->decimal('difference', 10, 2);
            $table->string('movement_type');
            $table->text('observation')->nullable();
            $table->decimal('product_stock', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_details');
    }
};
