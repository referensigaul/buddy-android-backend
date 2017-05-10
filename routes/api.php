<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('user/cadastrar', [
	'uses' => 'Api\UserController@cadastrar',
	'as' => 'api.postUserCadastrar'
]);


Route::group(['middleware' => ['auth:api']], function(){

	Route::group(['prefix' => 'user'], function () {

		Route::get('', [
			'uses' => 'Api\UserController@index',
			'as' => 'api.getUserIndex'
		]);

		Route::put('', [
			'uses' => 'Api\UserController@update',
			'as' => 'api.getUserUpdate'
		]);


		Route::get('logout', [
			'uses' => 'Api\UserController@logout',
			'as' => 'api.getUserLogout'
		]);

		Route::get('grupos-pessoais', [
			'uses' => 'Api\UserController@gruposPessoais',
			'as' => 'api.getUserGruposPessoais'
		]);

		Route::get('grupos', [
			'uses' => 'Api\UserController@grupos',
			'as' => 'api.getUserGrupos'
		]);

		Route::get('cronogramas-pessoais', [
			'uses' => 'Api\UserController@CronogramasPessoais',
			'as' => 'api.getUserCronogramasPessoais'
		]);

		Route::get('cronogramas', [
			'uses' => 'Api\UserController@Cronogramas',
			'as' => 'api.getUserCronogramas'
		]);

	});

	Route::group(['prefix' => 'atividade'], function () {

		Route::get('', [
			'uses' => 'Api\AtividadeController@index',
			'as' => 'api.getAtividadeIndex'
		]);

		Route::get('find-by-cronograma/{id}', [
			'uses' => 'Api\AtividadeController@findByCronograma',
			'as' => 'api.findByCronograma'
		]);


		Route::post('', [
			'uses' => 'Api\AtividadeController@store',
			'as' => 'api.getAtividadeStore'
		]);

		Route::put('{id}', [
			'uses' => 'Api\AtividadeController@update',
			'as' => 'api.getAtividadeUpdate'
		]);

		Route::delete('{id}', [
			'uses' => 'Api\AtividadeController@destroy',
			'as' => 'api.getAtividadeDestroy'
		]);

	});

    Route::resource('grupo', 'Api\GrupoController');
    Route::resource('cronograma', 'Api\CronogramaController');

	Route::post('cronograma/compartilhar', [
		'uses' => 'Api\CronogramaController@compartilhar',
		'as' => 'api.getCronogramaCompartilhar'
	]);

	Route::post('cronograma/compartilhar', [
		'uses' => 'Api\CronogramaController@removerCompartilhar',
		'as' => 'api.getCronogramaRemoverCompartilhar'
	]);

});


