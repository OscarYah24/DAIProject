<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description', // Cambiado de 'descripcion' a 'description'
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Los atributos que deben estar ocultos para la serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Los nombres de los atributos que deben ser tratados como fechas.
     *
     * @var array<int, string>
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Relación: Una categoría puede tener múltiples artículos
     * 
     * @return HasMany
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Accessor para obtener el nombre en formato title case
     * 
     * @param string $value
     * @return string
     */
    public function getFormattedNameAttribute(): string
    {
        return ucwords(strtolower($this->name));
    }

    /**
     * Accessor para obtener la descripción truncada
     * 
     * @param int $length
     * @return string
     */
    public function getShortDescriptionAttribute(): string
    {
        return \Str::limit($this->description, 50);
    }

    /**
     * Mutator para el nombre - siempre guardar en title case
     * 
     * @param string $value
     * @return void
     */
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = ucwords(strtolower(trim($value)));
    }

    /**
     * Mutator para la descripción - limpiar espacios
     * 
     * @param string $value
     * @return void
     */
    public function setDescriptionAttribute($value): void
    {
        $this->attributes['description'] = trim($value);
    }

    /**
     * Scope para obtener categorías que tienen artículos
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithArticles(Builder $query): Builder
    {
        return $query->has('articles');
    }

    /**
     * Scope para obtener categorías sin artículos
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithoutArticles(Builder $query): Builder
    {
        return $query->doesntHave('articles');
    }

    /**
     * Scope para buscar categorías por nombre o descripción
     * 
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope para obtener categorías ordenadas por popularidad (número de artículos)
     * 
     * @param Builder $query
     * @param string $direction
     * @return Builder
     */
    public function scopeOrderByPopularity(Builder $query, string $direction = 'desc'): Builder
    {
        return $query->withCount('articles')
                    ->orderBy('articles_count', $direction);
    }

    /**
     * Scope para obtener categorías recientes
     * 
     * @param Builder $query
     * @param int $days
     * @return Builder
     */
    public function scopeRecent(Builder $query, int $days = 7): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Verificar si la categoría tiene artículos
     * 
     * @return bool
     */
    public function hasArticles(): bool
    {
        return $this->articles()->exists();
    }

    /**
     * Obtener el conteo de artículos
     * 
     * @return int
     */
    public function getArticlesCount(): int
    {
        return $this->articles()->count();
    }

    /**
     * Obtener el artículo más reciente de esta categoría
     * 
     * @return Article|null
     */
    public function getLatestArticle(): ?Article
    {
        return $this->articles()->latest()->first();
    }

    /**
     * Obtener estadísticas de la categoría
     * 
     * @return array
     */
    public function getStats(): array
    {
        $articles = $this->articles;
        
        return [
            'total_articles' => $articles->count(),
            'latest_article' => $articles->sortByDesc('created_at')->first(),
            'oldest_article' => $articles->sortBy('created_at')->first(),
            'avg_content_length' => $articles->avg(function ($article) {
                return strlen($article->content);
            }),
            'total_authors' => $articles->pluck('author')->unique()->count(),
        ];
    }

    /**
     * Método para duplicar una categoría (crear una copia)
     * 
     * @param string|null $newName
     * @return Category
     */
    public function duplicate(?string $newName = null): Category
    {
        $newCategory = $this->replicate();
        $newCategory->name = $newName ?? $this->name . ' (Copia)';
        $newCategory->save();
        
        return $newCategory;
    }

    /**
     * Verificar si la categoría se puede eliminar (no tiene artículos)
     * 
     * @return bool
     */
    public function canBeDeleted(): bool
    {
        return !$this->hasArticles();
    }

    /**
     * Obtener categorías más populares
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getMostPopular(int $limit = 10)
    {
        return static::withCount('articles')
                    ->orderBy('articles_count', 'desc')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Obtener estadísticas generales de categorías
     * 
     * @return array
     */
    public static function getGeneralStats(): array
    {
        return [
            'total' => static::count(),
            'with_articles' => static::has('articles')->count(),
            'without_articles' => static::doesntHave('articles')->count(),
            'recent' => static::recent()->count(),
            'most_popular' => static::getMostPopular(1)->first(),
        ];
    }

    /**
     * The "booted" method of the model.
     * 
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($category) {
            $exists = static::whereRaw('LOWER(name) = ?', [strtolower($category->name)])->exists();
            if ($exists) {
                throw new \Exception('Ya existe una categoría con este nombre.');
            }
        });

        static::updating(function ($category) {
            $exists = static::whereRaw('LOWER(name) = ?', [strtolower($category->name)])
                           ->where('id', '!=', $category->id)
                           ->exists();
            if ($exists) {
                throw new \Exception('Ya existe una categoría con este nombre.');
            }
        });

        static::deleting(function ($category) {
            if ($category->hasArticles()) {
                throw new \Exception('No se puede eliminar una categoría que tiene artículos asociados.');
            }
        });
    }
}