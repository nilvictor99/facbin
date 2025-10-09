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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('ruc');
            $table->string('business_name');
            $table->string('trade_name');
            $table->text('address');
            $table->string('ubigeo');
            $table->string('urbanization')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->date('founding_date')->nullable();
            $table->string('industry')->nullable();
            $table->string('status')->default('active');
            $table->string('logo')->nullable();
            $table->string('primary_contact')->nullable();
            $table->string('sponsor')->nullable();
            $table->string('default_shipping_mode')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
