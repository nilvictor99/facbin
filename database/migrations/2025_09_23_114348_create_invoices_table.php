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
            $table->decimal('subtotal')->nullable();
            $table->decimal('igv_percentage', 5, 2)->default(18.00);
            $table->decimal('igv')->nullable();
            $table->decimal('total', 10, 2);
            $table->string('estado')->default('pendiente');
            $table->foreignId('customer_id')->nullable()->constrained();
            $table->foreignId('company_id')->nullable()->constrained();
            $table->string('xml_path')->nullable();
            $table->string('cdr_path')->nullable();
            $table->string('ticket')->nullable();
            $table->string('doc_respuesta')->nullable();
            $table->text('cadena_ticket')->nullable();
            $table->text('url_ticket')->nullable();
            $table->text('url_a4')->nullable();
            $table->text('cadena_xml')->nullable();
            $table->text('url_xml')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
