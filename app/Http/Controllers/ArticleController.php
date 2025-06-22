<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Constructor - Aplicar middleware de autenticación
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar lista de artículos
     */
    public function index()
    {
        $articles = Article::with('category')->orderBy('created_at', 'desc')->get();
        return view('articles.index', compact('articles'));
    }

    /**
     * Mostrar formulario para crear nuevo artículo
     */
    public function create()
    {
        $categories = Category::all();
        return view('articles.create', compact('categories'));
    }

    /**
     * Guardar nuevo artículo
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id'
        ]);

        Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'author' => $request->author,
            'category_id' => $request->category_id
        ]);

        return redirect()->route('articles.index')
                        ->with('success', 'Artículo creado exitosamente.');
    }

    /**
     * Mostrar un artículo específico
     */
    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    /**
     * Mostrar formulario para editar artículo
     */
    public function edit(Article $article)
    {
        $categories = Category::all();
        return view('articles.edit', compact('article', 'categories'));
    }

    /**
     * Actualizar artículo
     */
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id'
        ]);

        $article->update([
            'title' => $request->title,
            'content' => $request->content,
            'author' => $request->author,
            'category_id' => $request->category_id
        ]);

        return redirect()->route('articles.index')
                        ->with('success', 'Artículo actualizado exitosamente.');
    }

    /**
     * Eliminar artículo
     */
    public function destroy(Article $article)
    {
        $article->delete();
        
        return redirect()->route('articles.index')
                        ->with('success', 'Artículo eliminado exitosamente.');
    }
}