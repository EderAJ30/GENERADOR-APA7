<?php

namespace Database\Factories;

use App\Models\Referencia;
use App\Models\Editorial;
use App\Models\TiposReferencia;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReferenciaFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente al factory.
     *
     * @var string
     */
    protected $model = Referencia::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'titulo' => ucfirst($this->faker->words($this->faker->numberBetween(3, 8), true)),
            'anio_publicacion' => $this->faker->numberBetween(1995, 2026),
            'fecha_exacta' => $this->faker->optional(0.5)->dateTimeThisDecade(),
            'volumen' => $this->faker->optional(0.5)->numberBetween(1, 12),
            'numero' => $this->faker->optional(0.5)->numberBetween(1, 4),
            'paginas' => (string) $this->faker->numberBetween(10, 500), // Casteado a string por el tipo en BD
            'isbn_issn' => $this->faker->optional(0.7)->isbn13(),
            'doi' => $this->faker->optional(0.4)->regexify('10\.\d{4,9}/[-._;()/:A-Z0-9]+'),
            'url' => $this->faker->optional(0.6)->url(),
            'resumen' => $this->faker->optional(0.5)->paragraph(),
            
            // Relaciones foráneas sincronizadas con la arquitectura de la base de datos
            'id_tipo_referencia' => TiposReferencia::inRandomOrder()->first()->id_tipo_referencia ?? 1,
            'id_editorial' => Editorial::inRandomOrder()->first()->id_editorial ?? 1,
            
            // Forzamos el ID 1 para que coincida perfectamente con el usuario Christian creado en la Fase 0 del Seeder
            'id_usuario' => 1, 
        ];
    }
}