<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Titulo extends Model
{
    // Adicione esta linha para corrigir o caminho do banco
    protected $fillable = ['nome'];
    protected $table = 'titulos'; // caso sua tabela não esteja no plural em inglês
}


