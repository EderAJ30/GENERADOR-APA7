<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\TiposReferencia;
use App\Models\Editorial;
use App\Models\Autor;
use App\Models\Materia;
use App\Models\Tema;
use App\Models\Referencia;
use App\Models\Rol;
use App\Models\Usuario;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ======================================================================
        // FASE 0: ROLES Y USUARIOS INICIALES (Obligatorios para Llaves Foráneas)
        // ======================================================================
        
        // Creamos los roles base si no existen
        Rol::firstOrCreate(['id_rol' => 1], ['nombre' => 'Admin']);
        Rol::firstOrCreate(['id_rol' => 2], ['nombre' => 'Usuario']);

        // Creamos tu usuario dueño del catálogo forzando que tenga el ID 1
        Usuario::firstOrCreate(
            ['id_usuario' => 1], 
            [
                'nombre_usuario' => 'Christian',
                'paterno_usuario' => 'Lara',
                'materno_usuario' => 'Martinez',
                'email' => 'chris.lara@aragon.unam.mx',
                'password' => bcrypt('password123'),
                'rol_id' => 2, 
                'estatus' => 1
            ]
        );

        // Creamos la cuenta de Administrador fija con el ID 2
        Usuario::firstOrCreate(
            ['id_usuario' => 2], 
            [
                'nombre_usuario' => 'Administrador',
                'paterno_usuario' => 'Sistema',
                'materno_usuario' => 'ICO',
                'email' => 'admin@aragon.unam.mx',
                'password' => bcrypt('admin123'),
                'rol_id' => 1, // Rol de Administrador
                'estatus' => 1
            ]
        );

        // ======================================================================
        // FASE 1: CATÁLOGOS BASE INDEPENDIENTES
        // ======================================================================

        // Creamos la división/área obligatoria para las materias
        $areaDefecto = Area::firstOrCreate(['nombre' => 'Ingeniería en Computación']);

        $tipos = ['Libro', 'Artículo de Revista', 'Tesis', 'Sitio Web', 'Conferencia'];
        foreach ($tipos as $tipo) {
            TiposReferencia::firstOrCreate(['nombre' => $tipo]);
        }

        $editoriales = ['Alfaomega', 'McGraw-Hill', 'Pearson', 'IEEE', 'Springer', 'O\'Reilly', 'UNAM FES Aragón'];
        foreach ($editoriales as $editorial) {
            Editorial::firstOrCreate(['nombre' => $editorial]);
        }

        // Creamos el pool de 150 Autores utilizando su Factory
        $autores = Autor::factory()->count(150)->create();

        // Creamos materias clave de ICO incluyendo id_area y semestre obligatorio
        $materiasClave = ['Estructuras de Datos', 'Programación Orientada a Objetos', 'Bases de Datos', 'Redes de Computadoras', 'Inteligencia Artificial', 'Ingeniería de Software'];
        $materias = collect();
        foreach ($materiasClave as $materia) {
            $materias->push(
                Materia::firstOrCreate([
                    'nombre' => $materia,
                    'id_area' => $areaDefecto->id_area,
                    'semestre' => rand(1, 8) 
                ])
            );
        }

        // Creamos un pool de Temas/Etiquetas
        $temasClave = ['Laravel', 'Docker', 'React', 'MySQL', 'Python', 'Ciberseguridad', 'Machine Learning', 'TailwindCSS', 'Vite', 'Cloud Computing'];
        $temas = collect();
        foreach ($temasClave as $tema) {
            $temas->push(Tema::firstOrCreate(['nombre' => $tema]));
        }

        // ======================================================================
        // FASE 2: CREACIÓN DE REFERENCIAS Y TABLAS PIVOTE (MÉTODOS NATIVOS)
        // ======================================================================
        
        Referencia::factory()->count(1050)->create()->each(function ($referencia) use ($autores, $materias, $temas) {
            
            // 1. Relación con Autores (A través del método hasMany definido en tu modelo)
            $autoresAleatorios = $autores->random(rand(1, 3));
            foreach ($autoresAleatorios as $autor) {
                $referencia->referencia_autores()->create([
                    'id_autor' => $autor->id_autor
                ]);
            }

            // 2. Relación con Materias (Usando attach para belongsToMany y cubriendo el campo tipo_bibliografia)
            $materiasAleatorias = $materias->random(rand(1, 2))->pluck('id_materia')->toArray();
            foreach ($materiasAleatorias as $idMateria) {
                $referencia->materias()->attach($idMateria, ['tipo_bibliografia' => 'Básica']);
            }

            // 3. Relación con Temas (Usando attach directo)
            $temasAleatorios = $temas->random(rand(1, 4))->pluck('id_tema')->toArray();
            $referencia->temas()->attach($temasAleatorios);
        });
    }
}