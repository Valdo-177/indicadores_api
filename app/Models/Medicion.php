<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicion extends Model
{
    use HasFactory;

    protected $table = 'mediciones';

    protected $fillable = [
        'fecha',
        'valor',
        'descripcion',
        'indicador_id',
    ];

    public function indicador()
    {
        return $this->belongsTo(Indicador::class);
    }
}
