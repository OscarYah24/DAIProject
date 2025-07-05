<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Esta migración cambia el nombre de la columna 'descripcion' a 'description'
     * en la tabla categories para seguir las convenciones de Laravel en inglés.
     */
    public function up(): void
    {
        // Verificar si la tabla categories existe
        if (!Schema::hasTable('categories')) {
            throw new Exception('La tabla categories no existe. Ejecuta primero las migraciones base.');
        }

        Schema::table('categories', function (Blueprint $table) {
            // Verificar si la columna 'descripcion' existe antes de renombrarla
            if (Schema::hasColumn('categories', 'descripcion')) {
                // Renombrar la columna de 'descripcion' a 'description'
                $table->renameColumn('descripcion', 'description');
            }
        });

        // Mensaje informativo para el desarrollador
        echo "✅ Columna 'descripcion' renombrada a 'description' en la tabla categories.\n";
    }

    /**
     * Reverse the migrations.
     * 
     * En caso de rollback, volvemos al nombre original 'descripcion'
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Verificar si la columna 'description' existe antes de renombrarla
            if (Schema::hasColumn('categories', 'description')) {
                // Renombrar la columna de 'description' a 'descripcion'
                $table->renameColumn('description', 'descripcion');
            }
        });

        echo "↩️ Columna 'description' revertida a 'descripcion' en la tabla categories.\n";
    }
};