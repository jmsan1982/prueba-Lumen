<?php

namespace App\Http\Controllers;

use App\Models\Pregunta;
use App\Models\PreguntaTest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreguntasController extends BaseController
{
    //creo funcion y establezco los parametros a buscar
    public function getTests($oposicionId, $tipoTestId, $bloqueId){

        //hago la consulta a la bbdd filtrando por variables y agrupando, utilizo un count para los que tengan 5 o mas preguntas
        $tests = PreguntaTest::join('preguntas_config_tests', 'preguntas_tests.testID', '=', 'preguntas_config_tests.id')
            ->join('preguntas_bloque', 'preguntas_tests.preguntaID', '=', 'preguntas_bloque.preguntaID')
            ->select('preguntas_tests.testID', 'preguntas_config_tests.nombre')
            ->where('preguntas_tests.oposicionID', '=', $oposicionId)
            ->where('preguntas_config_tests.test_tipoID', '=', $tipoTestId)
            ->where('preguntas_bloque.bloqueID', '=', $bloqueId)
            ->groupBy('preguntas_tests.testID', 'preguntas_config_tests.nombre')
            ->havingRaw('count(*) >= 5')
            ->get();

        //devuelvo el resultado
        return response()->json(['tests' => $tests]);
    }

    //segundo metodo donde tambien saco el estado de las preguntas
    public function getTestWithEstados($oposicionId, $tipoTestId, $bloqueId, $estado){

        //la consulta es muy parecida a la anterior pero hago un join de preguntas_estados a preguntas
        $tests = PreguntaTest::join('preguntas_config_tests', 'preguntas_tests.testID', '=', 'preguntas_config_tests.id')
            ->join('preguntas_bloque', 'preguntas_tests.preguntaID', '=', 'preguntas_bloque.preguntaID')
            ->join('preguntas_respuesta', 'preguntas_tests.oposicionID', '=', 'preguntas_respuesta.oposicionID')
            ->join('preguntas', 'preguntas_tests.preguntaID', '=', 'preguntas.id')
            ->join('preguntas_estados', 'preguntas.id', '=', 'preguntas_estados.preguntaID')
            ->where('preguntas_tests.oposicionID', '=', $oposicionId)
            ->where('preguntas_config_tests.test_tipoID', '=', $tipoTestId)
            ->where('preguntas_bloque.bloqueID', '=', $bloqueId)
            ->where('preguntas_estados.estado', '=', $estado)
            ->groupBy('preguntas_tests.testID', 'preguntas_config_tests.nombre')
            ->havingRaw('count(*) >= 5')
            ->select('preguntas_tests.testID', 'preguntas_config_tests.nombre')
            ->get();

        //devuelvo el resultado
        return response()->json(['tests' => $tests]);
    }

    public function corregirTest(Request $request) {

        $params = new \stdClass();
        $input = $request->all();
        foreach ( $input as $filter => $value ) {
            $params->$filter = $value;
        }
        $total_preguntas_falladas = 0;
        $total_preguntas_acertadas = 0;
        $total_preguntas_no_contestadas = 0;

        if(isset($params->preguntas)){
            $preguntas_json = $params->preguntas;
            foreach($preguntas_json as $pregunta_json){
                $acertada = 1; //Asumimos la pregunta como acertada ya que cuando exista un fallo ya siempre se marcará como fallada.
                $contestada = null;
                $fallos = 0;
                $respondida = false; //Esta variable determina si el usuario ha contestado la pregunta.

                //comprobacion de que pregunta este marcada y cumpla condiciones de bloque id y oposicion id
                if (isset($pregunta_json["marcado"]) && $params->bloqueID == 1 && $params->oposicionID == 2) {
                    //inicializo variables por si acaso no existieran
                    $params->falladas = $params->falladas ?? 0;
                    $params->acertadas = $params->acertadas ?? 0;

                    //compruebo si esta marcada y y la respuesta es vacia si es asi es correcta, si no la respuesta es incorrecta
                    if ($pregunta_json["marcado"] == 1 && empty($pregunta_json["respuestas"])) {
                        $acertada = 1;
                        $params->acertadas++;
                        $respondida = true;
                    } else if ($pregunta_json["marcado"] == 1 && !empty($pregunta_json["respuestas"])) {
                        foreach ($pregunta_json["respuestas"] as $respuesta) {
                            $acertada = -1;
                            $contestada = $respuesta["id"];
                        }
                        $params->falladas++;
                        $fallos++;
                        $respondida = true;
                    } else if ($pregunta_json["marcado"] == -1 && !empty($pregunta_json["respuestas"])) {
                        //sino esta marcada y no esta vacia es correcta
                        foreach ($pregunta_json["respuestas"] as $respuesta) {
                            $acertada = 1;
                            $contestada = $respuesta["id"];
                            $params->acertadas++;
                        }
                        $respondida = true;
                    } else if ($pregunta_json["marcado"] == -1 && empty($pregunta_json["respuestas"])) {
                        //si no esta marcada y esta vacia incorrecta
                        $acertada = -1;
                        $params->falladas++;
                        $fallos++;
                        $respondida = true;
                    } else {
                        //en caso de que no se cumpla ninguna la dejo a cero
                        $acertada = 0;
                    }
                } else {
                    $params->falladas = $params->falladas ?? 0;
                    $params->acertadas = $params->acertadas ?? 0;

                    foreach ($pregunta_json["respuestas"] as $respuesta) {
                        //si la respuyesta es conestada y correcta guardo el id de la respuesy aumento ademas de poner que ya esta contestada
                        if ($respuesta["contestada"] && $respuesta["correcta"]) {
                            $contestada = $respuesta["id"];
                            $params->acertadas++;
                            $respondida = true;
                        } else if ($respuesta["contestada"] && !$respuesta["correcta"]) {
                            //si la respuesta es contestada pero incorrecta guardo la incorrecta e incrementolos fallos
                            $contestada = $respuesta["id"];
                            $acertada = 0;
                            $params->falladas++;
                            $fallos++;
                            $respondida = true;
                        } else if (!$respuesta["contestada"]) {
                            //si bloqueid y oposicionid es 1 compruebo si la respuesta es correcta si lo es sumo en 1 fallos si no sumo acertadas
                            if ($params->bloqueID == 1 && $params->oposicionID == 1) {
                                if ($respuesta["correcta"]) {
                                    $params->falladas++;
                                    $contestada = 0;
                                    $fallos++;
                                } else {
                                    $params->acertadas++;
                                    $contestada = 0;
                                }
                            }
                        }
                    }
                }
                //Actualizamos contadores de preguntas
                if(!$respondida){
                    $total_preguntas_no_contestadas++;
                } else if ($acertada == 1) {
                    $total_preguntas_acertadas++;
                } else {
                    $total_preguntas_falladas++;
                }
            }
        }

        echo json_encode(array(
            "preguntas_acertadas" => $total_preguntas_acertadas,
            "total_preguntas_no_contestadas" => $total_preguntas_no_contestadas,
            "total_preguntas_falladas" => $total_preguntas_falladas,
        ));

    }
}
