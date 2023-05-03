<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateTablePreguntasEstados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preguntas_estados', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('preguntaID');
            $table->foreign('preguntaID')->references('id')->on('preguntas');
            $table->string('estado');
            $table->timestamp('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preguntas_estados');
    }
}
