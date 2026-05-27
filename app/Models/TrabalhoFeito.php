<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrabalhoFeito extends Model
{
    protected $table = 'trabalho_feito';

    protected $fillable = ['user_id', 'title', 'description', 'images', 'avaliacao', 'preco', 'tempo_gasto', 'localizacao'];

    protected $casts = [
        'images' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
