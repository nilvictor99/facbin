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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('serie');
            $table->string('correlativo');
            $table->string('tipo_doc');
            $table->datetime('fecha_emision');
            $table->decimal('total', 10, 2);
            $table->string('estado')->default('pendiente');
            $table->foreignId('client_id')->constrained();
            $table->foreignId('company_id')->constrained();
            $table->string('xml_path')->nullable();
            $table->string('cdr_path')->nullable();
            $table->string('ticket')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
