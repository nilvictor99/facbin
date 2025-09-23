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
        Schema::create('voideds', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fec_generacion');
            $table->timestamp('fec_comunicacion');
            $table->string('correlativo');
            $table->foreignId('company_id')->constrained();
            $table->string('estado');
            $table->string('ticket');
            $table->string('xml_path');
            $table->string('cdr_path');
            $table->json('details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voideds');
    }
};
