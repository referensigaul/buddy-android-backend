<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Grupo;
use DB;

class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $idUser = $request->user()->id;
        $grupos = DB::table('grupos')->where('user_id', '=', $idUser)->get();
        return response()->json($grupos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $idUser = $request->user()->id;

        $grupo = Grupo::create([
            'descricao' => $request->descricao,
            'user_id' => $idUser
        ]);
        return response()->json($grupo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        $idUser = $request->user()->id;
        $grupo = DB::table('grupos')
            ->where('id', '=', $id)
            ->where('user_id', '=', $idUser)
            ->get();

        return response()->json($grupo);
     
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
        $idUser = $request->user()->id;
        $grupo = DB::table('grupos')
            ->where('id', '=', $id)
            ->where('user_id', '=', $idUser)
            ->first();

        $msg = "";

        if($grupo){
            $grupoAtualizar = Grupo::find($grupo->id);
            $grupoAtualizar->descricao = $request->descricao;
            $grupoAtualizar->save();
            $msg = ['erro' => false, 'msg' => 'Grupo atualizado'];
        }else{
            $msg = ['erro' => true, 'msg' => 'Grupo não encontrado'];
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
        $idUser = $request->user()->id;
        $grupo = DB::table('grupos')
            ->where('id', '=', $id)
            ->where('user_id', '=', $idUser)
            ->first();

        $msg = "";

        if($grupo){
            $grupoDeletar = Grupo::find($grupo->id);
            $grupoDeletar->delete();
            $msg = ['erro' => false, 'msg' => 'Grupo deletado'];
        }else{
            $msg = ['erro' => true, 'msg' => 'Grupo não encontrado'];
        }

        return response()->json($msg);
    }
}
