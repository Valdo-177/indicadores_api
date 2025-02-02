<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicador extends Model
{
    use HasFactory;

    protected $table = 'Indicadores';

    protected $fillable = [
        'nombre',
        'valor_esperado',
        'valor_minimo',
        'valor_maximo',
    ];

    public function mediciones()
    {
        return $this->hasMany(Medicion::class);
    }

    public function getColorCódigoAttribute()
    {
        return ColorCódigo::where('indicador_id', $this->id)->first()?->codigo ?? 'N/A';
    }
}
