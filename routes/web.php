<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MockupsController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;

Route::get('/', [MockupsController::class, 'index'])->name('mockups.home');

Route::get('/design', [MockupsController::class, 'design'])->name('design');
Route::get('/resources', [MockupsController::class, 'resources'])->name('resources');
Route::get('/prototyping', [MockupsController::class, 'prototyping'])->name('prototyping');
Route::get('/code', [MockupsController::class, 'code'])->name('code');
Route::get('/ux', [MockupsController::class, 'ux'])->name('ux');

Route::get('/search', [MockupsController::class, 'search'])->name('search');

Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

    Route::resource('articles', ArticleController::class)->names([
        'index' => 'articles.index',
        'create' => 'articles.create',
        'store' => 'articles.store',
        'show' => 'articles.show',
        'edit' => 'articles.edit',
        'update' => 'articles.update',
        'destroy' => 'articles.destroy'
    ]);

    Route::resource('categories', CategoryController::class)->names([
        'index' => 'categories.index',
        'create' => 'categories.create',
        'store' => 'categories.store',
        'show' => 'categories.show',
        'edit' => 'categories.edit',
        'update' => 'categories.update',
        'destroy' => 'categories.destroy'
    ]);
    
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/search', [CategoryController::class, 'search'])->name('search');
        
        Route::get('/statistics', [CategoryController::class, 'stats'])->name('stats');
    });

    Route::get('/api/categories', function () {
        return response()->json([
            'success' => true,
            'categories' => \App\Models\Category::select('id', 'name')->orderBy('name')->get()
        ]);
    })->name('api.categories');

    Route::post('/api/categories/validate-name', function (\Illuminate\Http\Request $request) {
        $request->validate(['name' => 'required|string']);
        
        $exists = \App\Models\Category::where('name', $request->name)
                                   ->when($request->category_id, function ($query, $categoryId) {
                                       return $query->where('id', '!=', $categoryId);
                                   })
                                   ->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Este nombre ya está en uso.' : 'Nombre disponible.'
        ]);
    })->name('api.categories.validate-name');
});

Route::get('/welcome', function () {
    return view('welcome');
});

if (app()->environment('local')) {
    
    Route::get('/route-cache-clear', function () {
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        return 'Cache de rutas limpiado';
    });
    
    Route::get('/routes-list', function () {
        $routes = collect(\Illuminate\Support\Facades\Route::getRoutes())->map(function ($route) {
            return [
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'methods' => $route->methods(),
                'middleware' => $route->middleware()
            ];
        });
        
        return response()->json($routes);
    });
}