<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Atividade extends Model
{
    protected $fillable = [
        'cronograma_id', 'descricao', 'valor', 'dtentrega'
    ];

    public function cronograma(){
    	return $this->belongsTo('App\Cronograma');
    }

    function getDtentregaAttribute($value)
	{
	    return Carbon::parse($value)->format('d/m/Y');
	}
}
