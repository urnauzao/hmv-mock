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
        Schema::create('estabelecimentos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 60)->nullable();
            $table->string('telefone', 15)->nullable();
            $table->string('email', 60)->nullable();
            $table->string('cnpj')->nullable();
            $table->string('imagem', 300)->nullable();
            $table->string('site', 300)->nullable();
            $table->enum('tipo', ['0', '1', '2', '3'])
            ->comment('0 -> Hospital, 1 -> Pronto Socorro, 2 -> Clínica, 3 -> Outros');
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
        Schema::dropIfExists('estabelecimentos');
    }
};
