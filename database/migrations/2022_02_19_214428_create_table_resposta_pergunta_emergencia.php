<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respostas_perguntas_emergencia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('questionario_emergencia_id');
            $table->foreign('questionario_emergencia_id')->references('id')->on('questionarios_emergencia');
            $table->unsignedBigInteger('pergunta_emergencia_id');
            $table->foreign('pergunta_emergencia_id')->references('id')->on('perguntas_emergencia');
            $table->json('resposta')->nullable();
            $table->string('resposta_texto', 300);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('respostas_perguntas_emergencia');
    }
};
