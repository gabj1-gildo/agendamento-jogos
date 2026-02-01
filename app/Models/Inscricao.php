<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model
{
    protected $table = 'inscricoes';
    protected $fillable = ['id_user', 'id_jogo', 'status', 'data_inscricao'];

    // Relacionamentos
    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function jogo() {
        return $this->belongsTo(Jogo::class, 'id_jogo');
    }

    // Escopos de busca (Facilitam filtrar no gerenciamento)
    public function scopePendentes($query) {
        return $query->where('status', 'pendente');
    }

    public function scopeConfirmadas($query) {
        return $query->where('status', 'confirmado');
    }
}