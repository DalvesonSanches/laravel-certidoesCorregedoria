<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certidao extends Model{
    use HasFactory;

    // tabela com schema
    protected $table = 'corregedoria.certidoes';

    // campos que podem ser preenchidos via create()
    protected $fillable = [
        'cod_autenticidade',	
        'militar_cpf',
        'militar_nome',	
        'corregedor_cpf',
        'data_validade',	
        'situacao',	
        'arquivo_nome',
        'numero_certidao',
        'corregedor_nome',	
        'militar_rg',
        'militar_matricula',
    ];
}