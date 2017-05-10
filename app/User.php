<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function gruposPessoais(){
        return $this->hasMany('App\Grupo');
    }

    public function grupos(){
        return $this->belongsToMany('App\Grupo', 'user_grupo');
    }

    public function cronogramasPessoais(){
        return $this->hasMany('App\Cronograma');
    }

    public function cronogramas(){
        return $this->belongsToMany('App\Cronograma', 'cronograma_user');
    }
}
