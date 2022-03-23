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
        Schema::create('associativa_perfil_estabelecimento', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('perfil_id')->nullable();
            $table->foreign('perfil_id')->references('id')->on('perfis');
            $table->unsignedBigInteger('estabelecimento_id')->nullable();
            $table->foreign('estabelecimento_id')->references('id')->on('estabelecimentos');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('associativa_perfil_estabelecimento');
    }
};
