<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $idUser = $request->user()->id;
        $user = DB::table('users')
            ->where('id', '=', $idUser)
            ->first();

        $msg = "";

        if($user){
            $userAtualizar = User::find($user->id);
            $userAtualizar->name = $request->name;
            $userAtualizar->save();
            $msg = ['erro' => false, 'msg' => 'Usuário atualizado'];
        }else{
            $msg = ['erro' => true, 'msg' => 'Usuário não encontrado'];
        }

        return response()->json($msg);
    }

    public function cadastrar(Request $request)

    {
        
        if($request->name == "" || $request->email == "" || $request->password == ""){
            return response()->json(['erro' => true, 'msg' => 'Todos os campos são obrigatórios']);
        }

        $user = DB::table('users')
            ->where('email', '=', $request->email)
            ->first();

        $msg = "";

        if(!$user){

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $msg = ['erro' => false, 'msg' => 'Cadastrado com Sucesso'];
        }else{
            $msg = ['erro' => true, 'msg' => 'E-Mail já está em uso'];
        }

        return response()->json($msg);
    }

    /**
     * Retorna todos os grupos não compartilhados
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function gruposPessoais(Request $request)
    {
        $user = $request->user();
        return response()->json($user->gruposPessoais);
    }

    /**
     * Retorna todos os grupos compartilhados
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function grupos(Request $request)
    {
        $user = $request->user();
        return response()->json($user->grupos);
    }

    /**
     * Retorna todos os cronogramas não compartilhados
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cronogramasPessoais(Request $request)
    {
        $user = $request->user();
        return response()->json($user->cronogramasPessoais);
    }

    /**
     * Retorna todos os cronogramas compartilhados
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cronogramas(Request $request)
    {
        $user = $request->user();
        return response()->json($user->cronogramas);
    }

    public function logout(Request $request)
    {

        $request->user()->token()->revoke();

        $json = [
            'erro' => false
        ];
        return response()->json($json, '200');
    }

}
