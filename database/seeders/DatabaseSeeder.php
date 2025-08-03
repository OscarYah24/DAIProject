<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuarios de prueba si no existen
        if (User::count() === 0) {
            // Usuario de prueba específico
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

            // Usuarios adicionales para testing
            User::factory(4)->create();
        }

        // Ejecutar el seeder de posts
        $this->call([
            PostSeeder::class,
        ]);
    }
}