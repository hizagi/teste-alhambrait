<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaEnderecos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enderecos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rua');
            $table->string('numero');
            $table->string('cep');
            $table->unsignedInteger('id_cliente');
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('restrict');
            $table->unsignedInteger('id_cidade');
            $table->foreign('id_cidade')->references('id')->on('cidades')->onDelete('restrict');
            $table->unsignedInteger('id_estado');
            $table->foreign('id_estado')->references('id')->on('estados')->onDelete('restrict');
            $table->timestamp('data_cadastro')->nullable();
            $table->timestamp('data_alteracao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enderecos');
    }
}
