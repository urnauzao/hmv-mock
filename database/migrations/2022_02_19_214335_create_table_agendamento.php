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
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->date('data')->useCurrent();
            $table->enum('situacao', ['0', '1', '2', '3', '4'])
            ->default('4')
            ->comment('0 -> Agendado, 1 -> Na espera, 2 -> Em realização, 3 -> Realizado, 4 -> Não realizado');
            $table->text('observacoes');
            $table->unsignedBigInteger('paciente_perfil_id')->nullable();
            $table->foreign('paciente_perfil_id')->references('id')->on('perfis');
            $table->unsignedBigInteger('medico_perfil_id')->nullable();
            $table->foreign('medico_perfil_id')->references('id')->on('perfis');
            $table->unsignedBigInteger('estabelecimento_id')->nullable();
            $table->foreign('estabelecimento_id')->references('id')->on('estabelecimentos');
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
        Schema::dropIfExists('agendamentos');
    }
};
