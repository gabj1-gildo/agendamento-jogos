<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    // Adicione os campos que podem ser preenchidos pelo formulário
    protected $fillable = ['nome', 'endereco', 'tipo'];
    
    // Se a sua tabela no banco não se chamar 'locals', force o nome correto:
    protected $table = 'locais'; 
}