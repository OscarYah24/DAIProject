<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Exception;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar lista de categorías
     * 
     * @return View
     */
    public function index(): View
    {
        try {
            $categories = Category::withCount('articles')
                               ->orderBy('name', 'asc')
                               ->get();

            return view('categories.index', compact('categories'));
        } catch (Exception $e) {
            return view('categories.index')->with('error', 'Error al cargar las categorías: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para crear nueva categoría
     * 
     * @return View
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Guardar nueva categoría
     * 
     * @param CategoryRequest $request
     * @return RedirectResponse
     */
    public function store(CategoryRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $category = Category::create([
                'name' => $request->validated()['name'],
                'description' => $request->validated()['description'],
            ]);

            DB::commit();

            return redirect()
                ->route('categories.index')
                ->with('success', 'Categoría "' . $category->name . '" creada exitosamente.');

        } catch (Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al crear la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar una categoría específica
     * 
     * @param Category $category
     * @return View
     */
    public function show(Category $category): View
    {
        try {
            $category->load(['articles' => function($query) {
                $query->orderBy('created_at', 'desc');
            }]);

            return view('categories.show', compact('category'));
        } catch (Exception $e) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'Error al mostrar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar categoría
     * 
     * @param Category $category
     * @return View
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Actualizar categoría
     * 
     * @param CategoryRequest $request
     * @param Category $category
     * @return RedirectResponse
     */
    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $category->update([
                'name' => $request->validated()['name'],
                'description' => $request->validated()['description'],
            ]);

            DB::commit();

            return redirect()
                ->route('categories.index')
                ->with('success', 'Categoría "' . $category->name . '" actualizada exitosamente.');

        } catch (Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al actualizar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar categoría
     * 
     * @param Category $category
     * @return RedirectResponse
     */
    public function destroy(Category $category): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $articlesCount = $category->articles()->count();
            
            if ($articlesCount > 0) {
                return redirect()
                    ->route('categories.index')
                    ->with('warning', 
                        'No se puede eliminar la categoría "' . $category->name . '" porque tiene ' . 
                        $articlesCount . ' artículo(s) asociado(s). Elimina o reasigna los artículos primero.'
                    );
            }

            $categoryName = $category->name;
            
            $category->delete();

            DB::commit();

            return redirect()
                ->route('categories.index')
                ->with('success', 'Categoría "' . $categoryName . '" eliminada exitosamente.');

        } catch (Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->route('categories.index')
                ->with('error', 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Buscar categorías (para AJAX o búsqueda en tiempo real)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'La búsqueda debe tener al menos 2 caracteres.'
            ]);
        }

        try {
            $categories = Category::where('name', 'LIKE', "%{$query}%")
                               ->orWhere('description', 'LIKE', "%{$query}%")
                               ->withCount('articles')
                               ->orderBy('name')
                               ->limit(10)
                               ->get();

            return response()->json([
                'success' => true,
                'data' => $categories,
                'count' => $categories->count()
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la búsqueda: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de categorías (para dashboard)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats()
    {
        try {
            $stats = [
                'total_categories' => Category::count(),
                'categories_with_articles' => Category::has('articles')->count(),
                'categories_without_articles' => Category::doesntHave('articles')->count(),
                'most_used_category' => Category::withCount('articles')
                                              ->orderBy('articles_count', 'desc')
                                              ->first(),
                'recent_categories' => Category::orderBy('created_at', 'desc')
                                             ->limit(5)
                                             ->get()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ], 500);
        }
    }
}