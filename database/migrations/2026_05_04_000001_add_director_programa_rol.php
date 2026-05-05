<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('rol')->insert([
            'name'       => 'DirectorPrograma',
            'status_id'  => 1,
            'created_by' => null,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('rol')->where('name', 'DirectorPrograma')->delete();
    }
};
