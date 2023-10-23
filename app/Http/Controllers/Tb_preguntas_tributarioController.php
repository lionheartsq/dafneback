<?php

namespace App\Http\Controllers;

use App\Models\Tb_avances_tributario;
use App\Models\Tb_enunciados_tributario;
use App\Models\Tb_preguntas_tributario;
use Illuminate\Http\Request;

class Tb_preguntas_tributarioController extends Controller
{
    public function index(Request $request)
    {
        $preguntas_tributario = Tb_preguntas_tributario::orderBy('tb_preguntas_tributario.id','asc')
        ->get();

        return [
            'estado' => 'Ok',
            'preguntas_tributario' => $preguntas_tributario
        ];
    }

    public function indexOne(Request $request)
    {
        $preguntas_tributario = Tb_preguntas_tributario::orderBy('tb_preguntas_tributario.id','desc')
        ->where('tb_preguntas_tributario.id','=',$request->id)
        ->get();

        return [
            'estado' => 'Ok',
            'preguntas_tributario' => $preguntas_tributario
        ];
    }

    public function store(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');

        try {
            $tb_preguntas_tributario=new Tb_preguntas_tributario();
            $tb_preguntas_tributario->pregunta=$request->pregunta;
            $tb_preguntas_tributario->estado=1;

            if ($tb_preguntas_tributario->save()) {
                return response()->json([
                    'estado' => 'Ok',
                    'message' => 'preguntas_tributario creada con éxito'
                   ]);
            } else {
                return response()->json([
                    'estado' => 'Error',
                    'message' => 'preguntas_tributario no pudo ser creada'
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
            $tb_preguntas_tributario=Tb_preguntas_tributario::findOrFail($request->id);
            $tb_preguntas_tributario->pregunta=$request->pregunta;
            $tb_preguntas_tributario->estado='1';

            if ($tb_preguntas_tributario->save()) {
                return response()->json([
                    'estado' => 'Ok',
                    'message' => 'preguntas_tributario actualizada con éxito'
                   ]);
            } else {
                return response()->json([
                    'estado' => 'Error',
                    'message' => 'preguntas_tributario no pudo ser actualizada'
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
            $tb_preguntas_tributario=Tb_preguntas_tributario::findOrFail($request->id);
            $tb_preguntas_tributario->estado='0';

            if ($tb_preguntas_tributario->save()) {
                return response()->json([
                    'estado' => 'Ok',
                    'message' => 'preguntas_tributario desactivada con éxito'
                   ]);
            } else {
                return response()->json([
                    'estado' => 'Error',
                    'message' => 'preguntas_tributario no pudo ser desactivada'
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
            $tb_preguntas_tributario=Tb_preguntas_tributario::findOrFail($request->id);
            $tb_preguntas_tributario->estado='1';

            if ($tb_preguntas_tributario->save()) {
                return response()->json([
                    'estado' => 'Ok',
                    'message' => 'preguntas_tributario activada con éxito'
                   ]);
            } else {
                return response()->json([
                    'estado' => 'Error',
                    'message' => 'preguntas_tributario no pudo ser activada'
                   ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error interno'], 500);
        }

    }

    public function validateFlow(Request $request){
        $idUsuario=$request->idUsuario;
        $idPregunta=$request->idP;

        $cant_preguntas_simulacion = Tb_avances_tributario::where('tb_avances_tributario.idUsuario','=',$idUsuario)->count();

        if($cant_preguntas_simulacion>0){
            $max_preguntas_simulacion = Tb_avances_tributario::where('tb_avances_tributario.idUsuario','=',$idUsuario)

            ->select('tb_avances_tributario.idExterno')
            ->orderBy('preguntas_tributario.id','desc')
            ->first();
        }else{
            $max_preguntas_simulacion = 1;
        }

        $pregunta_simulacion=Tb_preguntas_tributario::where('tb_preguntas_tributario.id','=',$max_preguntas_simulacion)->get();

        return [
            'estado' => 'Ok',
            'pregunta_simulacion' => $pregunta_simulacion
        ];
    }

    public function preFlow(Request $request){
        $idUsuario=$request->idUsuario;
        $idPregunta=$request->idP;

        $pregunta_simulacion=Tb_preguntas_tributario::where('tb_preguntas_tributario.id','=',$idPregunta)->get();

        return [
            'estado' => 'Ok',
            'pregunta_simulacion' => $pregunta_simulacion
        ];
    }

    public function nextFlow(Request $request){
        $idUsuario=$request->idUsuario;
        $idPregunta=$request->idP;
        $valor=$request->valor;

        $pregunta_simulacion=Tb_preguntas_tributario::where('tb_preguntas_tributario.id','=',$idPregunta)->get();

        foreach($pregunta_simulacion as $vueltaP){
            $cadenaP = $vueltaP->pregunta;
            }

        switch ($idPregunta) {
            case '1':
                switch ($valor) {
                    case '1':
                        // Código a ejecutar si pregunta es 1 y entra por si'
                        try {
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=1;
                            $tb_avances_tributario->cadena=$cadenaP;
                            $tb_avances_tributario->pregunta=1;
                            $tb_avances_tributario->enunciado=0;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=2;
                            return response()->json([
                                'estado' => 'Ok',
                                'message' => $next_question
                               ]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Ocurrió un error interno'], 500);
                        }
                        break;
                    case '2':
                        // Código a ejecutar si  si pregunta es 1 y entra por no'
                        $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',1)->get();

                        foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->pregunta;
                            }

                        try {
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=1;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=2;
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
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=2;
                            $tb_avances_tributario->cadena=$cadenaP;
                            $tb_avances_tributario->pregunta=1;
                            $tb_avances_tributario->enunciado=0;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=3;
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
                        $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',2)->get();

                        foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->pregunta;
                            }

                        try {
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=2;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=3;
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
                        $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',5)->get();

                        foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                        try {
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=5;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();


                            $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',6)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                                $cadenaE = $vueltaE->enunciado;
                                }

                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=6;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();

                            $next_question=5;
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
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=3;
                            $tb_avances_tributario->cadena=$cadenaP;
                            $tb_avances_tributario->pregunta=1;
                            $tb_avances_tributario->enunciado=0;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();

                            $next_question=4;
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
                        $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',4)->get();

                        foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                        try {
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=4;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();

                            $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',6)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                                $cadenaE = $vueltaE->enunciado;
                                }

                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=6;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();

                            $next_question=5;
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
                        $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',3)->get();

                        foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                        try {
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=3;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();

                            $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',6)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                                $cadenaE = $vueltaE->enunciado;
                                }

                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=6;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();

                            $next_question=5;
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
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=5;
                            $tb_avances_tributario->cadena=$cadenaP;
                            $tb_avances_tributario->pregunta=1;
                            $tb_avances_tributario->enunciado=0;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=6;
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
                        $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',7)->get();

                        foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                        try {
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=7;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=6;
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
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=6;
                            $tb_avances_tributario->cadena=$cadenaP;
                            $tb_avances_tributario->pregunta=1;
                            $tb_avances_tributario->enunciado=0;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=9;
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
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=6;
                            $tb_avances_tributario->cadena=$cadenaP;
                            $tb_avances_tributario->pregunta=1;
                            $tb_avances_tributario->enunciado=0;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=7;
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
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=7;
                            $tb_avances_tributario->cadena=$cadenaP;
                            $tb_avances_tributario->pregunta=1;
                            $tb_avances_tributario->enunciado=0;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=8;
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
                        $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',8)->get();

                        foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                        try {
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=8;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=16;
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
                        $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',9)->get();

                        foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                        try {
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=9;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=17;
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
                        $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',10)->get();

                        foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                        try {
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=10;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=17;
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
                        $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',12)->get();

                        foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                        try {
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=12;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=11;
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
                        $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',11)->get();

                        foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                        try {
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=11;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=10;
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
                        $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',15)->get();

                        foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                        try {
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=15;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=17;
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
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=10;
                            $tb_avances_tributario->cadena=$cadenaP;
                            $tb_avances_tributario->pregunta=1;
                            $tb_avances_tributario->enunciado=0;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=12;
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
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=11;
                            $tb_avances_tributario->cadena=$cadenaP;
                            $tb_avances_tributario->pregunta=1;
                            $tb_avances_tributario->enunciado=0;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=13;
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
                        $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',17)->get();

                        foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                        try {
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=17;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=17;
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
                        $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',13)->get();

                        foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                        try {
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=13;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=17;
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
                        $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',14)->get();

                        foreach($enunciado_simulacion as $vueltaE){
                            $cadenaE = $vueltaE->enunciado;
                            }

                        try {
                            $tb_avances_tributario=new Tb_avances_tributario();
                            $tb_avances_tributario->idExterno=14;
                            $tb_avances_tributario->cadena=$cadenaE;
                            $tb_avances_tributario->pregunta=0;
                            $tb_avances_tributario->enunciado=1;
                            $tb_avances_tributario->idUsuario=$idUsuario;
                            $tb_avances_tributario->estado=1;
                            $tb_avances_tributario->save();
                            $next_question=17;
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
                            $enunciado_simulacion=Tb_enunciados_tributario::where('tb_enunciados_tributario.id','=',47)->get();

                            foreach($enunciado_simulacion as $vueltaE){
                                $cadenaE = $vueltaE->enunciado;
                                }

                            try {
                                $tb_avances_tributario=new Tb_avances_tributario();
                                $tb_avances_tributario->idExterno=47;
                                $tb_avances_tributario->cadena=$cadenaE;
                                $tb_avances_tributario->pregunta=0;
                                $tb_avances_tributario->enunciado=1;
                                $tb_avances_tributario->idUsuario=$idUsuario;
                                $tb_avances_tributario->estado=1;
                                $tb_avances_tributario->save();
                                $next_question=99;
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
                                $next_question=99;
                                return response()->json([
                                    'estado' => 'Ok',
                                    'message' => $next_question
                                   ]);
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
