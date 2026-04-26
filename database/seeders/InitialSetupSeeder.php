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
                'name' => 'ESTRUCTURA Y PROCESOS ACADÉMICOS',
                'description' => 'Las instituciones deberán garantizar la efectividad e integridad de la articulación entre las políticas, procesos, y
                procedimientos institucionales orientados a la gestión de los componentes formativos, pedagógicos, de evaluación, de
                interacción y de relación social, así como de las actividades académicas y los procesos formativos que se concretan en la
                oferta de programas académicos pertinentes y enmarcados en la universalidad del conocimiento.
                ',
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(6),
                'status_id' => 1
            ]
        ]);

        // CARACTERISTICA
        DB::table('caracteristicas')->insert([
            [
                'name' => 'Componentes formativos',
                'description' => 'Característica 17. Componentes formativo',
                'factor_id' => 1,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(36),
                'status_id' => 1,
                'responsable' => 1,
            ],
            [
                'name' => 'Componentes pedagógicos y de evaluación.',
                'description' => 'Característica 18. Componentes pedagógicos y de evaluación.',
                'factor_id' => 1,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(36),
                'status_id' => 1,
                'responsable' => 1,
            ],
            [
                'name' => 'Componente de interacción y relevancia social.',
                'description' => 'Característica 19. Componente de interacción y relevancia social',
                'factor_id' => 1,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(36),
                'status_id' => 1,
                'responsable' => 1,
            ],
            [
                'name' => 'Procesos de creación, modificación y ampliación de programas académicos.',
                'description' => 'Característica 20. Procesos de creación, modificación y ampliación de programas académicos.',
                'factor_id' => 1,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(36),
                'status_id' => 1,
                'responsable' => 1,
            ],

        ]);

        // ASPECTOS
        DB::table('aspectos')->insert([
            [
                'name' => 'Medición y valoración del efecto de las políticas, estrategias, recursos, ambientes y capacidades orientadas a la gestión de los procesos curriculares y extracurriculares.',
                'description' => 'Aspecto 41. Medición y valoración del efecto de las políticas, estrategias, recursos, ambientes y capacidades orientadas a la gestión de los procesos curriculares y extracurriculares.',
                'caracteristica_id' => 1,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(6),
                'status_id' => 1,
                'responsable' => 1,
            ],
            [
                'name' => 'Apreciación de la comunidad sobre la eficiencia de políticas y estrategias institucionales para la formación integral, flexibilidad curricular, internacionalización e interdisciplinariedad.',
                'description' => 'Aspecto 42. Apreciación de la comunidad sobre la eficiencia de políticas y estrategias institucionales para la formación integral, flexibilidad curricular, internacionalización e interdisciplinariedad.',
                'caracteristica_id' => 1,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(6),
                'status_id' => 1,
                'responsable' => 1,
            ],
            [
                'name' => 'Existencia de espacios de discusión y formación pedagógica para los profesores, orientados al logro de los resultados de aprendizaje, al mejoramiento continuo e innovación.',
                'description' => 'Aspecto 43. Existencia de espacios de discusión y formación pedagógica para los profesores, orientados al logro de los resultados de aprendizaje, al mejoramiento continuo e innovación.',
                'caracteristica_id' => 2,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(6),
                'status_id' => 1,
                'responsable' => 1,
            ],
            [
                'name' => 'Evidencias y resultados de las discusiones y la formación pedagógica de los profesores.',
                'description' => 'Aspecto 44. Evidencias y resultados de las discusiones y la formación pedagógica de los profesores.',
                'caracteristica_id' => 2,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(6),
                'status_id' => 1,
                'responsable' => 1,
            ],
            [
                'name' => 'Apreciación de la comunidad sobre seguimiento, evaluación y ajuste a las políticas, criterios y mecanismos de evaluación estudiantil.',
                'description' => 'Aspecto 45. Apreciación de la comunidad sobre seguimiento, evaluación y ajuste a las políticas, criterios y mecanismos de evaluación estudiantil.',
                'caracteristica_id' => 2,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(6),
                'status_id' => 1,
                'responsable' => 1,
            ],
            [
                'name' => 'Correspondencia entre los perfiles formativos y los objetivos de los programas académicos con las necesidades de formación profesional y laboral en contexto regional, nacional e internacional.',
                'description' => 'Aspecto 46. Correspondencia entre los perfiles formativos y los objetivos de los programas académicos con las necesidades de formación profesional y laboral en contexto regional, nacional e internacional.',
                'caracteristica_id' => 3,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(6),
                'status_id' => 1,
                'responsable' => 1,
            ],
            [
                'name' => 'Efecto de la evaluación sistemática y estructurada de las necesidades del contexto sobre la formación ofrecida.',
                'description' => 'Aspecto 47. Efecto de la evaluación sistemática y estructurada de las necesidades del contexto sobre la formación ofrecida.',
                'caracteristica_id' => 3,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(6),
                'status_id' => 1,
                'responsable' => 1,
            ],
            [
                'name' => 'Aplicación de políticas y procedimientos para la creación, modificación y ampliación de programas académicos.',
                'description' => 'Aspecto 48. Aplicación de políticas y procedimientos para la creación, modificación y ampliación de programas académicos.',
                'caracteristica_id' => 4,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(6),
                'status_id' => 1,
                'responsable' => 1,
            ],
            [
                'name' => 'Apreciación de la comunidad sobre la eficiencia de estas políticas y procedimientos.',
                'description' => 'Aspecto 49. Apreciación de la comunidad sobre la eficiencia de estas políticas y procedimientos.',
                'caracteristica_id' => 4,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(6),
                'status_id' => 1,
                'responsable' => 1,
            ]
        ]);
    }
}
