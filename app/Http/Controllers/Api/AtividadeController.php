<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Atividade;
use App\Cronograma;
use DB;

class AtividadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function findByCronograma(Request $request, $id){
    	$idUser = $request->user()->id;
        $cronograma = DB::table('cronogramas')
        	->where('id', '=', $id)->first();


        if($cronograma){
        	$cronogramaOBj = Cronograma::find($id);
        	$atividades = $cronogramaOBj->atividades;
        	return response()->json($atividades);
        }else{
        	$msg = ['erro' => true, 'msg' => 'Cronograma não encrontrado'];
        	return response()->json($msg);
        }	
        
       
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = $request->user()->id;
        $cronograma = Cronograma::find($request->cronograma_id);

        $msg = '';
        $dataArray = explode("/", $request->dtentrega);
        $dataFormatada = $dataArray[2].'-'.$dataArray[1].'-'.$dataArray[0];

        if($cronograma){

            $atividade = Atividade::create([
                'cronograma_id' => $request->cronograma_id,
                'descricao' => $request->descricao,
                'valor' => $request->valor,
                'dtentrega' => $dataFormatada
            ]);

            $msg = ['erro' => false, 'msg' => 'Atividade cadastrada'];

        }else{
            $msg = ['erro' => true, 'msg' => 'Cronograma não encontrado'];
        }
        return response()->json($msg);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $userId = $request->user()->id;
        $cronograma = Cronograma::find($request->cronograma_id);
        $atividade = Atividade::find($id);

        $msg = '';
        $dataArray = explode("/", $request->dtentrega);
        $dataFormatada = $dataArray[2].'-'.$dataArray[1].'-'.$dataArray[0];

        if($cronograma && $cronograma->user->id == $userId){


            if($atividade && $atividade->cronograma->id == $request->cronograma_id){

                $atividade->descricao = $request->descricao;
                $atividade->valor = $request->valor;
                $atividade->dtentrega = $dataFormatada;
                $atividade->save();
                $msg = ['erro' => false, 'msg' => 'Atividade atualizada'];

            }else{
                $msg = ['erro' => true, 'msg' => 'Atividade não encontrada'];

            }

        }else{
            $msg = ['erro' => true, 'msg' => 'Cronograma não encontrado'];
        }

        return response()->json($msg);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
    	$cronogramaID = $request->header("cronogramaID");
        $userId = $request->user()->id;
        $cronograma = Cronograma::find($cronogramaID);
        $atividade = Atividade::find($id);

        $msg = '';

        if($cronograma && $cronograma->user->id == $userId){


            if($atividade && $atividade->cronograma->id == $cronogramaID){

                $atividade->delete();
                $msg = ['erro' => false, 'msg' => 'Atividade deletada'];

            }else{
                $msg = ['erro' => true, 'msg' => 'Atividade não encontrada'];

            }

        }else{
            $msg = ['erro' => true, 'msg' => 'Cronograma não encontrado'];
        }

        return response()->json($msg);
    }
}
