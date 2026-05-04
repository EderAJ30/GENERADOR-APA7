<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CatalogosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('usuarios')->insert([
            'nombre_usuario'  => 'eder',
            'paterno_usuario' => 'avalos',
            'materno_usuario' => 'juarez',
            'email'           => 'ederaj30@gmail.com',
            'password'        => Hash::make('1234'), 
            'created_at'      => $now,
            'updated_at'      => $now,
        ]);

        $tipos = [
            ['nombre' => 'Libro', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Artículo de Revista', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Tesis', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Conferencia / Simposio', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Página Web', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Reporte Técnico', 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('tipos_referencia')->insert($tipos);

        $areas = [
            ['nombre' => 'Ingeniería en Computación', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Ciencias Básicas', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Ciencias Sociales y Humanidades', 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('areas')->insert($areas);

        $materias = [
            ['id_area' => 1, 'nombre' => 'Ingeniería de Software', 'semestre' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['id_area' => 1, 'nombre' => 'Bases de Datos', 'semestre' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['id_area' => 1, 'nombre' => 'Estructura de Datos y Algoritmos', 'semestre' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id_area' => 1, 'nombre' => 'Redes de Computadoras', 'semestre' => 7, 'created_at' => $now, 'updated_at' => $now],
            ['id_area' => 2, 'nombre' => 'Álgebra Lineal', 'semestre' => 2, 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('materias')->insert($materias);
    }
}

/* INSERT INTO usuarios (nombre_usuario, paterno_usuario, materno_usuario, email, password)
VALUES ('eder', 'avalos', 'juarez', 'ederaj30@gmail.com', '1234');

INSERT INTO tipos_referencia (nombre) VALUES 
('Libro'),
('Artículo de Revista'),
('Tesis'),
('Conferencia / Simposio'),
('Página Web'),
('Reporte Técnico');

INSERT INTO areas (nombre) VALUES 
('Ingeniería en Computación'),
('Ciencias Básicas'),
('Ciencias Sociales y Humanidades');

INSERT INTO materias (id_area, nombre, semestre) VALUES 
(1, 'Ingeniería de Software', 6),
(1, 'Bases de Datos', 5),
(1, 'Estructura de Datos y Algoritmos', 3),
(1, 'Redes de Computadoras', 7),
(2, 'Álgebra Lineal', 2); */