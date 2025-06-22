<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si ya existen categorías para evitar duplicados
        if (Category::count() > 0) {
            $this->command->info('Las categorías ya existen. Saltando...');
            return;
        }

        $categories = [
            [
                'name' => 'Electrónicos',
                'descripcion' => 'Productos electrónicos y tecnológicos'
            ],
            [
                'name' => 'Ropa y Accesorios',
                'descripcion' => 'Vestimenta, calzado y complementos'
            ],
            [
                'name' => 'Hogar y Jardín',
                'descripcion' => 'Artículos para el hogar y jardinería'
            ],
            [
                'name' => 'Deportes y Fitness',
                'descripcion' => 'Equipos deportivos y de ejercicio'
            ],
            [
                'name' => 'Libros y Educación',
                'descripcion' => 'Material educativo y literatura'
            ],
            [
                'name' => 'Belleza y Cuidado Personal',
                'descripcion' => 'Productos de belleza y cuidado'
            ],
            [
                'name' => 'Admisiones',
                'descripcion' => 'Información sobre admisiones universitarias'
            ],
            [
                'name' => 'Novedades',
                'descripcion' => 'Últimas noticias y novedades'
            ],
            [
                'name' => 'Matriculate en línea',
                'descripcion' => 'Procesos de matrícula en línea'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('Categorías creadas exitosamente.');
    }
}