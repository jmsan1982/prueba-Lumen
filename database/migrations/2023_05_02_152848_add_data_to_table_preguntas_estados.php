<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AddDataToTablePreguntasEstados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('preguntas_estados')->insert([
            [
                'preguntaID' => 1,
                'estado' => 'Publicada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 2,
                'estado' => 'Expirada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 3,
                'estado' => 'Derogada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 4,
                'estado' => 'Obsoleta',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 5,
                'estado' => 'Publicada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 6,
                'estado' => 'Expirada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 7,
                'estado' => 'Derogada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 8,
                'estado' => 'Obsoleta',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 9,
                'estado' => 'Publicada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 10,
                'estado' => 'Expirada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 11,
                'estado' => 'Derogada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 12,
                'estado' => 'Obsoleta',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 13,
                'estado' => 'Publicada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 14,
                'estado' => 'Expirada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 15,
                'estado' => 'Derogada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 16,
                'estado' => 'Obsoleta',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 17,
                'estado' => 'Publicada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 18,
                'estado' => 'Expirada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 19,
                'estado' => 'Derogada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 20,
                'estado' => 'Obsoleta',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 21,
                'estado' => 'Publicada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 22,
                'estado' => 'Expirada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 23,
                'estado' => 'Derogada',
                'fecha' => Carbon::now(),
            ],
            [
                'preguntaID' => 24,
                'estado' => 'Obsoleta',
                'fecha' => Carbon::now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('preguntas_estados')->truncate();
    }
}
