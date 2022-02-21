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
        Schema::create('historicos_atendimento', function (Blueprint $table) {
            $table->id();
            $table->string('relatorio', 1000)->nullable();
            $table->date('data')->useCurrent();
            $table->unsignedBigInteger('agendamento_id');
            $table->foreign('agendamento_id')->references('id')->on('agendamentos');
            $table->unsignedBigInteger('paciente_perfil_id');
            $table->foreign('paciente_perfil_id')->references('id')->on('perfis');
            $table->unsignedBigInteger('medico_perfil_id');
            $table->foreign('medico_perfil_id')->references('id')->on('perfis');
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
        Schema::dropIfExists('historicos_atendimento');
    }
};
