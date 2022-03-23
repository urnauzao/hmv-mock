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
        Schema::table('agendamentos', function (Blueprint $table) {            
            $table->unsignedBigInteger('socorrista_perfil_id')->nullable();
            $table->foreign('socorrista_perfil_id')->references('id')->on('perfis');
            $table->unsignedBigInteger('atendente_perfil_id')->nullable();
            $table->foreign('atendente_perfil_id')->references('id')->on('perfis');
            $table->index('paciente_perfil_id');
            $table->index('medico_perfil_id');
            $table->index('socorrista_perfil_id');
            $table->index('atendente_perfil_id');
            $table->index('situacao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropColumn(['socorrista_perfil_id', 'atendente_perfil_id']);
            $table->dropIndex('paciente_perfil_id');
            $table->dropIndex('medico_perfil_id');
            $table->dropIndex('socorrista_perfil_id');
            $table->dropIndex('atendente_perfil_id');
            $table->dropIndex('situacao');
        });
    }
};
