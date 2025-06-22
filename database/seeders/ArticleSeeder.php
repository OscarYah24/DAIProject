<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si ya existen artículos para evitar duplicados
        if (Article::count() > 0) {
            $this->command->info('Los artículos ya existen. Saltando...');
            return;
        }

        // Verificar que existan categorías
        if (Category::count() == 0) {
            $this->command->error('No hay categorías disponibles. Ejecuta primero CategorySeeder.');
            return;
        }

        $articles = [
            [
                'title' => 'Admisiones',
                'content' => 'Información completa sobre el proceso de admisiones en USAP. Conoce los requisitos, fechas importantes y procedimientos para ingresar a nuestra universidad. Ofrecemos diversas carreras y programas académicos de alta calidad.',
                'author' => 'Admisiones',
                'category_id' => Category::where('name', 'Admisiones')->first()->id ?? 1
            ],
            [
                'title' => 'Marco Alemán Watters',
                'content' => 'Perfil académico y profesional del destacado miembro de nuestra comunidad universitaria. Marco Alemán Watters ha contribuido significativamente al desarrollo académico y la excelencia educativa en USAP.',
                'author' => 'Novedades',
                'category_id' => Category::where('name', 'Novedades')->first()->id ?? 2
            ],
            [
                'title' => 'Matriculate en línea',
                'content' => 'Sistema de matrícula en línea disponible para todos los estudiantes de USAP. Proceso simplificado, seguro y eficiente para realizar tu matrícula desde la comodidad de tu hogar. Accede con tus credenciales institucionales.',
                'author' => 'Matriculate en línea',
                'category_id' => Category::where('name', 'Matriculate en línea')->first()->id ?? 3
            ],
            [
                'title' => 'iPhone 15 Pro Max - Tecnología Avanzada',
                'content' => 'El nuevo iPhone 15 Pro Max viene con chip A17 Pro, sistema de cámaras avanzado y diseño de titanio. Perfecto para profesionales y entusiastas de la tecnología que buscan el máximo rendimiento.',
                'author' => 'TechReviews',
                'category_id' => Category::where('name', 'Electrónicos')->first()->id ?? 1
            ],
            [
                'title' => 'Nike Air Max 2024 - Deportes',
                'content' => 'Las nuevas Nike Air Max 2024 combinan comodidad y estilo. Diseñadas para corredores que buscan rendimiento y durabilidad en cada paso. Tecnología de amortiguación avanzada.',
                'author' => 'SportsGear',
                'category_id' => Category::where('name', 'Deportes y Fitness')->first()->id ?? 4
            ],
            [
                'title' => 'Set de Cocina Premium',
                'content' => 'Conjunto completo de utensilios de cocina de acero inoxidable. Incluye ollas, sartenes y accesorios para crear las mejores recetas en casa. Calidad profesional para uso doméstico.',
                'author' => 'HomeProducts',
                'category_id' => Category::where('name', 'Hogar y Jardín')->first()->id ?? 3
            ],
            [
                'title' => 'Curso de Programación Web',
                'content' => 'Aprende desarrollo web desde cero con HTML, CSS, JavaScript y frameworks modernos. Incluye proyectos prácticos y certificación. Modalidad presencial y virtual disponible.',
                'author' => 'EduTech',
                'category_id' => Category::where('name', 'Libros y Educación')->first()->id ?? 5
            ],
            [
                'title' => 'Productos de Belleza Natural',
                'content' => 'Línea completa de productos de belleza elaborados con ingredientes naturales. Cuidado facial, corporal y capilar sin químicos dañinos. Resultados visibles y duraderos.',
                'author' => 'BeautyNatural',
                'category_id' => Category::where('name', 'Belleza y Cuidado Personal')->first()->id ?? 6
            ]
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }

        $this->command->info('Artículos creados exitosamente.');
    }
}