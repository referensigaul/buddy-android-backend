<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cronograma extends Model
{
    protected $fillable = [
        'descricao', 'user_id'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

   	public function users(){
        return $this->belongsToMany('App\User', 'cronograma_user');
    }

    public function atividades(){
    	return $this->hasMany('App\Atividade');
    }


}
