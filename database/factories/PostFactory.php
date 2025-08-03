<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para el modelo Post
 * 
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * El modelo asociado a este factory
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define el estado por defecto del modelo
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Título del post - frases entre 3-8 palabras
            'title' => $this->faker->sentence(
                $this->faker->numberBetween(3, 8), 
                false // Sin punto final
            ),
            
            // Contenido del post - párrafos entre 3-10
            'content' => $this->faker->paragraphs(
                $this->faker->numberBetween(3, 10), 
                true // Como string único
            ),
            
            // Relación con User - selecciona un usuario aleatorio existente
            // Si no hay usuarios, crea uno nuevo
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            
            // Timestamps realistas - posts creados en los últimos 6 meses
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => function (array $attributes) {
                // Updated_at siempre posterior o igual a created_at
                return $this->faker->dateTimeBetween(
                    $attributes['created_at'], 
                    'now'
                );
            },
        ];
    }

    /**
     * Estado para posts recientes (última semana)
     */
    public function recent(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'created_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            ];
        });
    }

    /**
     * Estado para posts largos (más contenido)
     */
    public function long(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'content' => $this->faker->paragraphs(
                    $this->faker->numberBetween(8, 15), 
                    true
                ),
            ];
        });
    }

    /**
     * Estado para posts con usuario específico
     */
    public function forUser(User $user): static
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }
}