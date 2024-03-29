<?php

namespace App\Http\Controllers;

use App\Models\Tb_avances_tributario_persona;
use App\Models\Tb_enlaces_tributario_persona;
use App\Models\Tb_enunciados_tributario_persona;
use App\Models\Tb_preguntas_tributario_persona;
use Illuminate\Http\Request;

class Tb_preguntas_tributario_personaController extends Controller
{
    public function index(Request $request)
    {
        $preguntas_tributario_persona = Tb_preguntas_tributario_persona::orderBy('tb_preguntas_tributario_persona.id','asc')
        ->get();

        return [
            'estado' => 'Ok',
            'preguntas_tributario_persona' => $preguntas_tributario_persona
        ];
    }

    public function indexOne(Request $request)
    {
        $preguntas_tributario_persona = Tb_preguntas_tributario_persona::orderBy('tb_preguntas_tributario_persona.id','desc')
        ->where('tb_preguntas_tributario_persona.id','=',$request->id)
        ->get();

        return [
            'estado' => 'Ok',
            'preguntas_tributario_persona' => $preguntas_tributario_persona
        ];
    }

    public function store(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');

        try {
            $tb_preguntas_tributario_persona=new Tb_preguntas_tributario_persona();
            $tb_preguntas_tributario_persona->pregunta=$request->pregunta;
            $tb_preguntas_tributario_persona->estado=1;

            if ($tb_preguntas_tributario_persona->save()) {
                return response()->json([
                    'estado' => 'Ok',
                    'message' => 'preguntas_tributario_persona creada con éxito'
                   ]);
            } else {
                return response()->json([
                    'estado' => 'Error',
                    'message' => 'preguntas_tributario_persona no pudo ser creada'
                   ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error interno'], 500);
        }

    }

    public function update(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');

        try {
            $tb_preguntas_tributario_persona=Tb_preguntas_tributario_persona::findOrFail($request->id);
            $tb_preguntas_tributario_persona->pregunta=$request->pregunta;
            $tb_preguntas_tributario_persona->estado='1';

            if ($tb_preguntas_tributario_persona->save()) {
                return response()->json([
                    'estado' => 'Ok',
                    'message' => 'preguntas_tributario_persona actualizada con éxito'
                   ]);
            } else {
                return response()->json([
                    'estado' => 'Error',
                    'message' => 'preguntas_tributario_persona no pudo ser actualizada'
                   ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error interno'], 500);
        }

    }

    public function deactivate(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');

        try {
            $tb_preguntas_tributario_persona=Tb_preguntas_tributario_persona::findOrFail($request->id);
            $tb_preguntas_tributario_persona->estado='0';

            if ($tb_preguntas_tributario_persona->save()) {
                return response()->json([
                    'estado' => 'Ok',
                    'message' => 'preguntas_tributario_persona desactivada con éxito'
                   ]);
            } else {
                return response()->json([
                    'estado' => 'Error',
                    'message' => 'preguntas_tributario_persona no pudo ser desactivada'
                   ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error interno'], 500);
        }

    }

    public function activate(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');

        try {
            $tb_preguntas_tributario_persona=Tb_preguntas_tributario_persona::findOrFail($request->id);
            $tb_preguntas_tributario_persona->estado='1';

            if ($tb_preguntas_tributario_persona->save()) {
                return response()->json([
                    'estado' => 'Ok',
                    'message' => 'preguntas_tributario_persona activada con éxito'
                   ]);
            } else {
                return response()->json([
                    'estado' => 'Error',
                    'message' => 'preguntas_tributario_persona no pudo ser activada'
                   ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error interno'], 500);
        }

    }

    public function validateFlow(Request $request){
        $idUsuario=$request->idUsuario;

        $cant_preguntas_simulacion = Tb_avances_tributario_persona::where('tb_avances_tributario_persona.idUsuario','=',$idUsuario)->count();

        if($cant_preguntas_simulacion>0){
            $preguntas_simulacion = Tb_avances_tributario_persona::where('tb_avances_tributario_persona.idUsuario','=',$idUsuario)
            ->orderBy('tb_avances_tributario_persona.id','asc')
            ->get();

            foreach($preguntas_simulacion as $vueltaP){
                $max_preguntas_simulacion = $vueltaP->next;
                }
        }else{
            $max_preguntas_simulacion = 1;
        }

        $pregunta_simulacion=Tb_preguntas_tributario_persona::where('tb_preguntas_tributario_persona.id','=',$max_preguntas_simulacion)->get();

        return [
            'estado' => 'Ok',
            'pregunta_simulacion' => $pregunta_simulacion
        ];
    }

    public function preFlow(Request $request){
        $idUsuario=$request->idUsuario;
        $idPregunta=$request->idP;

        $pregunta_simulacion=Tb_preguntas_tributario_persona::where('tb_preguntas_tributario_persona.id','=',$idPregunta)->get();

        return [
            'estado' => 'Ok',
            'pregunta_simulacion' => $pregunta_simulacion
        ];
    }

    public function guardarPregunta($idExterno, $cadenaP, $next, $idUsuario){
        $tb_avpreg_tribpers=new Tb_avances_tributario_persona();
        $tb_avpreg_tribpers->idExterno=$idExterno;
        $tb_avpreg_tribpers->cadena=$cadenaP;
        $tb_avpreg_tribpers->pregunta=1;
        $tb_avpreg_tribpers->enunciado=0;
        $tb_avpreg_tribpers->enlace=0;
        $tb_avpreg_tribpers->next=$next;
        $tb_avpreg_tribpers->idUsuario=$idUsuario;
        $tb_avpreg_tribpers->estado=1;
        $tb_avpreg_tribpers->save();
    }

    public function guardarEnunciado($idExterno, $cadenaE, $next, $idUsuario){
        $tb_avenun_tribpers=new Tb_avances_tributario_persona();
        $tb_avenun_tribpers->idExterno=$idExterno;
        $tb_avenun_tribpers->cadena=$cadenaE;
        $tb_avenun_tribpers->pregunta=0;
        $tb_avenun_tribpers->enunciado=1;
        $tb_avenun_tribpers->enlace=0;
        $tb_avenun_tribpers->next=$next;
        $tb_avenun_tribpers->idUsuario=$idUsuario;
        $tb_avenun_tribpers->estado=1;
        $tb_avenun_tribpers->save();
    }

    public function guardarEnlace($idExterno, $cadenaEn, $next, $idUsuario){
        $tb_avenla_tribpers=new Tb_avances_tributario_persona();
        $tb_avenla_tribpers->idExterno=$idExterno;
        $tb_avenla_tribpers->cadena=$cadenaEn;
        $tb_avenla_tribpers->pregunta=0;
        $tb_avenla_tribpers->enunciado=0;
        $tb_avenla_tribpers->enlace=1;
        $tb_avenla_tribpers->next=$next;
        $tb_avenla_tribpers->idUsuario=$idUsuario;
        $tb_avenla_tribpers->estado=1;
        $tb_avenla_tribpers->save();
    }

    public function nextFlow(Request $request){
        $idUsuario=$request->idUsuario;
        $idPregunta=$request->idP;
        $valor=$request->valor;

        $pregunta_simulacion=Tb_preguntas_tributario_persona::where('tb_preguntas_tributario_persona.id','=',$idPregunta)->get();

        foreach($pregunta_simulacion as $vueltaP){
            $cadenaP = $vueltaP->pregunta;
            }

        switch ($idPregunta) {
            case '1':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si pregunta es 1 y entra por si'
                        try {
                            $next_question=10;

                            //START MODIFY
                            $enunciado_simulacionP=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',1)->get();

                            foreach($enunciado_simulacionP as $vueltaEP){
                            $cadenaEP = $vueltaEP->enunciado;
                            $idE = $vueltaEP->id;
                            $this->guardarEnunciado($idE, $cadenaEP, $next_question, $idUsuario);
                            }

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',1)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(1, $cadenaEn, $next_question, $idUsuario);
                            //END MODIFY

                            $this->guardarPregunta(1, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',2)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(2, $cadenaE, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',9)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(9, $cadenaE, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',10)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(10, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno '.$e], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si  si pregunta es 1 y entra por no'
                        try {
                            $next_question=2;

                            //START MODIFY
                            $enunciado_simulacionP=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',1)->get();

                            foreach($enunciado_simulacionP as $vueltaEP){
                            $cadenaEP = $vueltaEP->enunciado;
                            $idE = $vueltaEP->id;
                            $this->guardarEnunciado($idE, $cadenaEP, $next_question, $idUsuario);
                            }

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',1)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(1, $cadenaEn, $next_question, $idUsuario);
                            //END MODIFY

                            $this->guardarPregunta(1, $cadenaP, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',2)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(2, $cadenaEn, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                    // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                    return [
                        'estado' => 'error',
                        'mensaje' => "El caso no existe"
                    ];
                }
                break;
            case '2':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=10;
                            $this->guardarPregunta(2, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',3)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(3, $cadenaE, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',2)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(2, $cadenaE, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',9)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(9, $cadenaE, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',10)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(10, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=3;
                            $this->guardarPregunta(2, $cadenaP, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',3)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(3, $cadenaEn, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                    // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                    return [
                        'estado' => 'error',
                        'mensaje' => "El caso no existe"
                    ];
                }
                break;
            case '3':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=5;
                            $this->guardarPregunta(3, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',4)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(4, $cadenaE, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',5)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(5, $cadenaEn, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=4;
                            $this->guardarPregunta(3, $cadenaP, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',4)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(4, $cadenaEn, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                    // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                    return [
                        'estado' => 'error',
                        'mensaje' => "El caso no existe"
                    ];
                }
                break;
            case '4':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=5;
                            $this->guardarPregunta(4, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',4)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(4, $cadenaE, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',5)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(5, $cadenaEn, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=6;
                            $this->guardarPregunta(4, $cadenaP, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',7)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(7, $cadenaEn, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                    // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                    return [
                        'estado' => 'error',
                        'mensaje' => "El caso no existe"
                    ];
                }
                break;
            case '5':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=7;
                            $this->guardarPregunta(5, $cadenaP, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',6)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(6, $cadenaEn, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=14;
                            $this->guardarPregunta(5, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',5)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(5, $cadenaE, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',8)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(8, $cadenaEn, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',6)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(6, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                    // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                    return [
                        'estado' => 'error',
                        'mensaje' => "El caso no existe"
                    ];
                }
                break;
            case '6':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=10;
                            $this->guardarPregunta(6, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',8)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(8, $cadenaE, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',9)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(9, $cadenaE, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',10)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(10, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=10;
                            $this->guardarPregunta(6, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',7)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(7, $cadenaE, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',9)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(9, $cadenaE, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',10)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(10, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                    // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                    return [
                        'estado' => 'error',
                        'mensaje' => "El caso no existe"
                    ];
                }
                break;
            case '7':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=14;
                            $this->guardarPregunta(7, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',5)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(5, $cadenaE, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',8)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(8, $cadenaEn, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',6)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(6, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=8;
                            $this->guardarPregunta(7, $cadenaP, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',9)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(9, $cadenaEn, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                    // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                    return [
                        'estado' => 'error',
                        'mensaje' => "El caso no existe"
                    ];
                }
                break;
            case '8':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=13;
                            $this->guardarPregunta(8, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',11)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(11, $cadenaE, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',17)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(17, $cadenaE, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',12)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(12, $cadenaEn, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=9;
                            $this->guardarPregunta(8, $cadenaP, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',10)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(10, $cadenaEn, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                    // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                    return [
                        'estado' => 'error',
                        'mensaje' => "El caso no existe"
                    ];
                }
                break;
            case '9':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=13;
                            $this->guardarPregunta(9, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',13)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(13, $cadenaE, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',17)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(17, $cadenaE, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',12)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(12, $cadenaEn, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=13;
                            $this->guardarPregunta(9, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',12)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(12, $cadenaE, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',17)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(17, $cadenaE, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',12)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(12, $cadenaEn, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                    // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                    return [
                        'estado' => 'error',
                        'mensaje' => "El caso no existe"
                    ];
                }
                break;
            case '10':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=11;
                            $this->guardarPregunta(10, $cadenaP, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',11)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(11, $cadenaEn, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',14)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(14, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=11;
                            $this->guardarPregunta(10, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',15)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(15, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                            // Código a ejecutar si $variable1 es 'valor1' pero $variable2 no coincide con ningún caso anterior
                    }
                break;
            case '11':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=12;
                            $this->guardarPregunta(11, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',16)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(16, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=17;
                            $this->guardarPregunta(11, $cadenaP, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                    // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                    return [
                        'estado' => 'error',
                        'mensaje' => "El caso no existe"
                    ];
                }
                break;
            case '12':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=17;
                            $this->guardarPregunta(12, $cadenaP, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',13)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(13, $cadenaEn, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',18)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(18, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=17;
                            $this->guardarPregunta(12, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',19)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(19, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                    // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                    return [
                        'estado' => 'error',
                        'mensaje' => "El caso no existe"
                    ];
                }
                break;
            case '13':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=14;
                            $this->guardarPregunta(13, $cadenaP, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',14)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(14, $cadenaEn, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',20)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(20, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=14;
                            $this->guardarPregunta(13, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',21)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(21, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                            // Código a ejecutar si $variable1 es 'valor1' pero $variable2 no coincide con ningún caso anterior
                    }
                break;
            case '14':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=17;
                            $this->guardarPregunta(14, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',24)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(24, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=15;
                            $this->guardarPregunta(14, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',22)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(22, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                    // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                    return [
                        'estado' => 'error',
                        'mensaje' => "El caso no existe"
                    ];
                }
                break;
            case '15':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=16;
                            $this->guardarPregunta(15, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',23)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(23, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=17;
                            $this->guardarPregunta(15, $cadenaP, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                    // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                    return [
                        'estado' => 'error',
                        'mensaje' => "El caso no existe"
                    ];
                }
                break;
            case '16':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=17;
                            $this->guardarPregunta(16, $cadenaP, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',15)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(15, $cadenaEn, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',25)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(25, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=17;
                            $this->guardarPregunta(16, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',26)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(26, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                    // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                    return [
                        'estado' => 'error',
                        'mensaje' => "El caso no existe"
                    ];
                }
                break;
            case '17':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=18;
                            $this->guardarPregunta(17, $cadenaP, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',16)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(16, $cadenaEn, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=99;
                            $this->guardarPregunta(17, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',27)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(27, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                    // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                    return [
                        'estado' => 'error',
                        'mensaje' => "El caso no existe"
                    ];
                }
                break;
            case '18':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                        try {
                            $next_question=99;
                            $this->guardarPregunta(18, $cadenaP, $next_question, $idUsuario);

                            $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',27)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                            $this->guardarEnunciado(27, $cadenaE, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                        try {
                            $next_question=19;
                            $this->guardarPregunta(18, $cadenaP, $next_question, $idUsuario);

                            $enlace_simulacion=Tb_enlaces_tributario_persona::where('tb_enlaces_tributario_persona.id','=',17)->get();

                            foreach($enlace_simulacion as $vueltaEn){
                            $cadenaEn = $vueltaEn->enlace;
                            }

                            $this->guardarEnlace(17, $cadenaEn, $next_question, $idUsuario);

                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    default:
                    // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                    return [
                        'estado' => 'error',
                        'mensaje' => "El caso no existe"
                    ];
                }
                break;
            case '19':
                    switch ($valor) {
                        case '1':
                            // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorA'
                            try {
                                $next_question=99;
                                $this->guardarPregunta(19, $cadenaP, $next_question, $idUsuario);

                                $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',28)->get();

                                foreach($enunciado_simulacion as $vueltaE){
                                $cadenaE = $vueltaE->enunciado;
                                }

                                $this->guardarEnunciado(28, $cadenaE, $next_question, $idUsuario);

                                return response()->json([
                                    'estado' => 'Ok',
                                    'message' => $next_question
                                   ]);
                            } catch (\Exception $e) {
                                return response()->json(['error' => 'Ocurrió un error interno'], 500);
                            }
                            break;
                        case '2':
                            // Código a ejecutar si $variable1 es 'valor1' y $variable2 es 'valorB'
                            try {
                                $next_question=99;
                                $this->guardarPregunta(19, $cadenaP, $next_question, $idUsuario);

                                $enunciado_simulacion=Tb_enunciados_tributario_persona::where('tb_enunciados_tributario_persona.id','=',27)->get();

                                foreach($enunciado_simulacion as $vueltaE){
                                $cadenaE = $vueltaE->enunciado;
                                }

                                $this->guardarEnunciado(27, $cadenaE, $next_question, $idUsuario);

                                return response()->json([
                                    'estado' => 'Ok',
                                    'message' => $next_question
                                   ]);
                            } catch (\Exception $e) {
                                return response()->json(['error' => 'Ocurrió un error interno'], 500);
                            }
                            break;
                        default:
                                // Código a ejecutar si $variable1 es 'valor1' pero $variable2 no coincide con ningún caso anterior
                        }
                break;
            default:
                // Código a ejecutar si $variable1 no coincide con ningún caso anterior
                return [
                    'estado' => 'error',
                    'mensaje' => "El caso no existe"
                ];
        }

        return [
            'estado' => 'Ok',
            'pregunta_simulacion' => $pregunta_simulacion
        ];
    }
}
