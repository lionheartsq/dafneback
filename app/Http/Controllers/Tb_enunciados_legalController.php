<?php

namespace App\Http\Controllers;

use App\Models\Tb_enunciados_legal;
use Illuminate\Http\Request;

class Tb_enunciados_legalController extends Controller
{
    public function index(Request $request)
    {
        $enunciados = Tb_enunciados_legal::orderBy('enunciados','asc')
        ->get();

        return [
            'estado' => 'Ok',
            'enunciados' => $enunciados
        ];
    }

    public function indexOne(Request $request)
    {
        $enunciados = Tb_enunciados_legal::orderBy('enunciados','desc')
        ->where('tb_enunciados_legal.id','=',$request->id)
        ->get();

        return [
            'estado' => 'Ok',
            'enunciados' => $enunciados
        ];
    }

    public function store(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');

        try {
            $tb_enunciados_legal=new Tb_enunciados_legal();
            $tb_enunciados_legal->enunciado=$request->enunciado;
            $tb_enunciados_legal->estado=1;

            if ($tb_enunciados_legal->save()) {
                return response()->json([
                    'estado' => 'Ok',
                    'message' => 'enunciados creada con éxito'
                   ]);
            } else {
                return response()->json([
                    'estado' => 'Error',
                    'message' => 'enunciados no pudo ser creada'
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
            $tb_enunciados_legal=Tb_enunciados_legal::findOrFail($request->id);
            $tb_enunciados_legal->enunciado=$request->enunciado;
            $tb_enunciados_legal->estado='1';

            if ($tb_enunciados_legal->save()) {
                return response()->json([
                    'estado' => 'Ok',
                    'message' => 'enunciados actualizada con éxito'
                   ]);
            } else {
                return response()->json([
                    'estado' => 'Error',
                    'message' => 'enunciados no pudo ser actualizada'
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
            $tb_enunciados_legal=Tb_enunciados_legal::findOrFail($request->id);
            $tb_enunciados_legal->estado='0';

            if ($tb_enunciados_legal->save()) {
                return response()->json([
                    'estado' => 'Ok',
                    'message' => 'enunciados desactivada con éxito'
                   ]);
            } else {
                return response()->json([
                    'estado' => 'Error',
                    'message' => 'enunciados no pudo ser desactivada'
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
            $tb_enunciados_legal=Tb_enunciados_legal::findOrFail($request->id);
            $tb_enunciados_legal->estado='1';

            if ($tb_enunciados_legal->save()) {
                return response()->json([
                    'estado' => 'Ok',
                    'message' => 'enunciados activada con éxito'
                   ]);
            } else {
                return response()->json([
                    'estado' => 'Error',
                    'message' => 'enunciados no pudo ser activada'
                   ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error interno'], 500);
        }

    }
}
