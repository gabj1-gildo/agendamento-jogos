<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;
    public function notas()
    {
        return $this->hasMany(Nota::class, 'user_id');
    }
}
