<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeder para poblar la tabla posts
 */
class PostSeeder extends Seeder
{
    /**
     * Ejecuta el seeder de posts
     */
    public function run(): void
    {
        // Verificar que existan usuarios antes de crear posts
        $userCount = User::count();
        
        if ($userCount === 0) {
            // Si no hay usuarios, crear algunos primero
            $this->command->info('No hay usuarios existentes. Creando usuarios...');
            User::factory(5)->create();
            $this->command->info('5 usuarios creados exitosamente.');
        }

        // Limpiar posts existentes si es necesario (opcional)
        // Post::truncate();

        $this->command->info('Creando 10 posts...');

        // Crear 10 posts usando el factory
        Post::factory()
            ->count(10)
            ->create();

        // Alternativa: Crear posts con diferentes estados
        /*
        Post::factory()
            ->count(5)
            ->create(); // 5 posts normales

        Post::factory()
            ->count(3)
            ->recent()
            ->create(); // 3 posts recientes

        Post::factory()
            ->count(2)
            ->long()
            ->create(); // 2 posts largos
        */

        $this->command->info('10 posts creados exitosamente.');

        // Mostrar estadísticas
        $totalPosts = Post::count();
        $totalUsers = User::count();
        
        $this->command->info("Total de posts en la BD: {$totalPosts}");
        $this->command->info("Total de usuarios en la BD: {$totalUsers}");
        
        // Verificar que todos los posts tienen usuario asignado
        $postsWithoutUser = Post::whereNull('user_id')->count();
        if ($postsWithoutUser > 0) {
            $this->command->warn("¡Atención! {$postsWithoutUser} posts sin usuario asignado.");
        } else {
            $this->command->info('✓ Todos los posts tienen usuario asignado correctamente.');
        }
    }
}