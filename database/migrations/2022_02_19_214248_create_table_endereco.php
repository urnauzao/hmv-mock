<?php

use Illuminate\Database\Eloquent\SoftDeletes;
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
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 60)->nullable();
            $table->string('tipo', 20)->nullable();
            $table->string('logradouro', 40)->nullable();
            $table->string('cep', 9)->nullable();
            $table->string('numero', 10)->nullable();
            $table->string('cidade', 40)->nullable();
            $table->string('estado',2)->nullable();
            $table->string('pais', 40)->default('Brasil')->nullable();
            $table->string('complemento', 60)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('enderecos');
    }
};
