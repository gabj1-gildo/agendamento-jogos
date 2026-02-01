<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jogo extends Model
{
    protected $fillable = [
        'responsavel_id', 
        'data_hora', 
        'vagas', 
        'vagas_disponiveis', 
        'titulo_id', 
        'descricao', 
        'local_id', 
        'data_hora_limite_inscricao', 
        'status'
    ];
    public function titulo()
    {
        // Define que 'titulo_id' no Jogo aponta para o 'id' na tabela Titulos
        return $this->belongsTo(Titulo::class, 'titulo_id');
    }

    public function local()
    {
        return $this->belongsTo(Local::class, 'local_id');
    }

    // buscar vagas restantes
    public function inscricoes()
    {
        return $this->hasMany(Inscricao::class, 'id_jogo');
    }

    // App\Models\Jogo.php

    public function responsavel()
    {
        // Indica que o id_responsavel do jogo aponta para o id de um utilizador
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    /**
     * Atributo virtual: $jogo->vagas_restantes
     */
    public function getVagasRestantesAttribute()
    {
        // Conta quantas inscrições confirmadas existem para este jogo
        $confirmados = $this->inscricoes()->where('status', 'confirmado')->count();
        
        // Retorna a diferença (garantindo que não seja menor que zero)
        return max(0, $this->vagas - $confirmados);
    }

    // fim de buscar vagas restantes
}
