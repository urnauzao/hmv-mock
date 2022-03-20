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
        Schema::create('perguntas_habitos_saude', function (Blueprint $table) {
            $table->id();
            $table->string('pergunta', 300);
            $table->enum('tipo', [
                'string', 'int', 'float', 'bool', 'multi'
            ])->default('string');
            $table->json('opcoes')->nullable();
            $table->integer('prioridade')->default(1);
            $table->boolean('obrigatoria')->default(false);
            $table->boolean('ativo')->default(true);
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
        Schema::dropIfExists('perguntas_habitos_saude');
    }
};
