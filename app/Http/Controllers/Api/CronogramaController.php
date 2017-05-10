<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cronograma;
use App\User;
use DB;

class CronogramaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $idUser = $request->user()->id;
        $user = User::find($idUser);

        $cronograma = DB::table('cronogramas')->where('user_id', '=', $idUser)->get();

        return response()->json($cronograma);
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

        $cronograma = Cronograma::create([
            'descricao' => $request->descricao,
            'user_id' => $idUser
        ]);
        return response()->json($cronograma, 201);
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
        $cronograma = DB::table('cronogramas')
            ->where('id', '=', $id)
            ->where('user_id', '=', $idUser)
            ->get();

        return response()->json($cronograma);
     
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
        $cronograma = DB::table('cronogramas')
            ->where('id', '=', $id)
            ->where('user_id', '=', $idUser)
            ->first();

        $msg = "";

        if($cronograma){
            $cronogramaAtualizar = Cronograma::find($cronograma->id);
            $cronogramaAtualizar->descricao = $request->descricao;
            $cronogramaAtualizar->save();
            $msg = ['erro' => false, 'msg' => 'Cronograma atualizado'];
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
        $idUser = $request->user()->id;
        $cronograma = DB::table('cronogramas')
            ->where('id', '=', $id)
            ->where('user_id', '=', $idUser)
            ->first();

        $msg = "";

        if($cronograma){

            $cronogramaDeletar = Cronograma::find($cronograma->id);

            $deletar = DB::table('atividades')
                ->where('cronograma_id', '=', $cronogramaDeletar->id)
                ->delete();

            $deletar = DB::table('cronograma_user')
                ->where('cronograma_id', '=', $cronogramaDeletar->id)
                ->delete();

            $cronogramaDeletar->delete();
            $msg = ['erro' => false, 'msg' => 'Cronograma deletado'];
        }else{
            $msg = ['erro' => true, 'msg' => 'Cronograma não encontrado'];
        }

        return response()->json($msg);
    }


    public function compartilhar(Request $request)
    {
        $idUser = $request->user()->id;
        $cronograma = DB::table('cronogramas')
            ->where('id', '=', $request->cronograma_id)
            ->where('user_id', '=', $idUser)
            ->first();

        $msg = "";

        if($cronograma){

            $userCompartilhar = User::find($request->user_id);

            if($userCompartilhar){


                $verifica = DB::table('cronograma_user')
                    ->where('cronograma_id', '=', $cronograma->id)
                    ->where('user_id', '=', $userCompartilhar->id)
                    ->first();

                if(!$verifica){

                    $cronograma = Cronograma::find($cronograma->id);
                    $userCompartilhar->cronogramas()->attach($cronograma);
                    $msg = ['erro' => false, 'msg' => 'Cronograma compartilhado'];

                }else{
                    $msg = ['erro' => true, 'msg' => 'Cronograma já foi compartilhado com este usuário'];
                }

            }else{
                $msg = ['erro' => true, 'msg' => 'Usuário não encontrado'];
            }
     

        }else{
            $msg = ['erro' => true, 'msg' => 'Cronograma não encontrado'];
        }

        return response()->json($msg);
    }


    public function removerCompartilhar(Request $request)
    {
        $idUser = $request->user()->id;
        $cronograma = DB::table('cronogramas')
            ->where('id', '=', $request->cronograma_id)
            ->where('user_id', '=', $idUser)
            ->first();

        $msg = "";

        if($cronograma){

            $userCompartilhar = User::find($request->user_id);

            if($userCompartilhar){


                $verifica = DB::table('cronograma_user')
                    ->where('cronograma_id', '=', $cronograma->id)
                    ->where('user_id', '=', $userCompartilhar->id)->get();

                    

                if(!$verifica->isEmpty()){

                    DB::table('cronograma_user')
                        ->where('cronograma_id', '=', $cronograma->id)
                        ->where('user_id', '=', $userCompartilhar->id)->delete();

                    $msg = ['erro' => false, 'msg' => 'Compartilhamento removido'];

                }else{
                    $msg = ['erro' => true, 'msg' => 'Compartilhamento não encontrado'];
                }

            }else{
                $msg = ['erro' => true, 'msg' => 'Usuário não encontrado'];
            }
     

        }else{
            $msg = ['erro' => true, 'msg' => 'Cronograma não encontrado'];
        }

        return response()->json($msg);
    }
}
