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
        Schema::create('respostas_perguntas_habitos_saude', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('habito_saude_id');
            $table->foreign('habito_saude_id')->references('id')->on('habitos_saude');
            $table->unsignedBigInteger('pergunta_habito_saude_id');
            $table->foreign('pergunta_habito_saude_id')->references('id')->on('perguntas_habitos_saude');
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
        Schema::dropIfExists('respostas_perguntas_habitos_saude');
    }
};
