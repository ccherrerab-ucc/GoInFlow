<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitialSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear usuario admin
        $admin = User::create([
            'name'     => 'Sistema',
            'first_surname' => 'Sistema',
            'email'    => 'sistema@ucatolica.edu.co',
            'password' => Hash::make('SistemaPassword'),
        ]);

        // 2. STATUS
        $statuses = [
            ['name' => 'active'],
            ['name' => 'inactive'],
            ['name' => 'pending'],
        ];

        DB::table('status')->insert($statuses);

        // 3. AREAS
        $areas = [
            [
                'name' => 'Sistema',
                'status_id' => 1,
            ],
            [
                'name' => 'Talento Humano',
                'status_id' => 1,
            ],
            [
                'name' => 'Financiera',
                'status_id' => 1,
            ],
            [
                'name' => 'Académica',
                'status_id' => 1,
            ],
            [
                'name' => 'Facultad de ingeniería',
                'status_id' => 1,
            ],
        ];

        // Agregar auditoría automáticamente
        $areas = array_map(function ($area) use ($admin) {
            return array_merge($area, [
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, $areas);

        DB::table('d_area')->insert($areas);

        // 4. ROLES
        $roles = [
            ['name' => 'Administrador'],
            ['name' => 'Director'],
            ['name' => 'Lider Caracteristica'],
            ['name' => 'Enlace'],
        ];

        $roles = array_map(function ($rol) use ($admin) {
            return array_merge($rol, [
                'status_id' => 1,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, $roles);
        // 5. DEPARTAMENTOS
        $departamentos = [
            [
                'name' => 'Desarrollo',
                'area_id' => 1,
                'status_id' => 1,
            ],
            [
                'name' => 'Soporte',
                'area_id' => 1,
                'status_id' => 1,
            ],
            [
                'name' => 'Selección',
                'area_id' => 2,
                'status_id' => 1,
            ],
            [
                'name' => 'Nómina',
                'area_id' => 2,
                'status_id' => 1,
            ],
            [
                'name' => 'Contabilidad',
                'area_id' => 3,
                'status_id' => 1,
            ],
            [
                'name' => 'Tesorería',
                'area_id' => 3,
                'status_id' => 1,
            ],
            [
                'name' => 'Registro Académico',
                'area_id' => 4,
                'status_id' => 1,
            ],
            [
                'name' => 'Ingeniería de Sistemas',
                'area_id' => 5,
                'status_id' => 1,
            ],
        ];

        $departamentos = array_map(function ($dep) use ($admin) {
            return array_merge($dep, [
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, $departamentos);
        DB::table('departamento')->insert($departamentos);

        DB::table('rol')->insert($roles);

        // 5. Actualizar usuario admin
        $admin->update([
            'id_area'   => 1,
            'id_rol'    => 1,
            'id_status' => 1,
        ]);
        // STATUS CNA — solo 3 valores según convención del proyecto
        DB::table('status_cna')->insert([
            ['name' => 'Activo'],    // id = 1
            ['name' => 'Inactivo'],  // id = 2
            ['name' => 'Suprimido'], // id = 3
        ]);

        // ESTADOS DE DOCUMENTO
        DB::table('estado_documento')->insert([
            ['name' => 'Borrador',     'created_at' => now(), 'updated_at' => now()], // id = 1
            ['name' => 'En revisión',  'created_at' => now(), 'updated_at' => now()], // id = 2
            ['name' => 'Aprobado',     'created_at' => now(), 'updated_at' => now()], // id = 3
            ['name' => 'Rechazado',    'created_at' => now(), 'updated_at' => now()], // id = 4
        ]);

        // FACTOR
        DB::table('factors')->insert([
            [                
                'name' => 'Factor 1',
                'description' => 'Descripción del factor 1',
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(6),
                'status_id' => 1
            ]
        ]);

        // CARACTERISTICA
        DB::table('caracteristicas')->insert([
            [
                'name' => 'Característica 1',
                'description' => 'Descripción de la característica',
                'factor_id' => 1,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(6),
                'status_id' => 1
            ]
        ]);

        // ASPECTOS
        DB::table('aspectos')->insert([
            [            
                'name' => 'Aspecto 1',
                'description' => 'Descripción del aspecto',
                'caracteristica_id' => 1,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(6),
                'status_id' => 1
            ]
        ]);
    }
}
