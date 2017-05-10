<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $fillable = [
        'descricao', 'user_id'
    ];

    public function userPessoal(){
        return $this->belongsTo('App\User');
    }

    public function users(){
        return $this->belongsToMany('App\User', 'user_grupo');
    }
}
