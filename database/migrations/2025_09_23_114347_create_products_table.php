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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('measure_unit_id')->constrained('measure_units');
            $table->integer('stock')->default(0);
            $table->decimal('sale_price', 10, 2);
            $table->decimal('purchase_price', 10, 2);
            $table->boolean('active')->default(true);
            $table->json('characters')->nullable();
            $table->text('instructions')->nullable();
            $table->foreignId('currency_id')->constrained('currencies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
