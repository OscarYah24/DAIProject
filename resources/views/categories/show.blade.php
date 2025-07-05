@extends('layouts.app')

@section('title', 'Categoría: ' . $category->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            {{-- Header de la página --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-0">
                        <i class="fas fa-tag me-2 text-info"></i>
                        {{ $category->name }}
                    </h2>
                    <p class="text-muted mb-0">Detalles y artículos de la categoría</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>
                        Editar
                    </a>
                    <button type="button" 
                            class="btn btn-outline-danger"
                            onclick="confirmDelete('{{ $category->id }}', '{{ $category->name }}', {{ $category->articles->count() }})">
                        <i class="fas fa-trash me-1"></i>
                        Eliminar
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Volver al Listado
                    </a>
                </div>
            </div>

            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('categories.index') }}">Categorías</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($category->name, 30) }}</li>
                </ol>
            </nav>

            {{-- Alertas y mensajes --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Información principal de la categoría --}}
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-tag text-white fa-2x"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h4 class="mb-2">{{ $category->name }}</h4>
                                    <p class="text-muted mb-3">{{ $category->description ?? $category->descripcion }}</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        @if($category->articles->count() > 0)
                                            <span class="badge bg-success fs-6">
                                                <i class="fas fa-newspaper me-1"></i>
                                                {{ $category->articles->count() }} 
                                                {{ $category->articles->count() == 1 ? 'Artículo' : 'Artículos' }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary fs-6">
                                                <i class="fas fa-inbox me-1"></i>
                                                Sin artículos
                                            </span>
                                        @endif
                                        <span class="badge bg-info fs-6">
                                            <i class="fas fa-calendar me-1"></i>
                                            Creada {{ $category->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Estadísticas rápidas --}}
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-6 col-lg-12 mb-3">
                            <div class="card border-primary text-center">
                                <div class="card-body">
                                    <i class="fas fa-newspaper fa-2x text-primary mb-2"></i>
                                    <h5 class="card-title">{{ $category->articles->count() }}</h5>
                                    <p class="card-text text-muted">Artículos</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-12 mb-3">
                            <div class="card border-info text-center">
                                <div class="card-body">
                                    <i class="fas fa-eye fa-2x text-info mb-2"></i>
                                    <h5 class="card-title">{{ $category->articles->sum('views') ?? 0 }}</h5>
                                    <p class="card-text text-muted">Visualizaciones</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Información detallada --}}
            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Información Detallada
                            </h5>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">ID:</dt>
                                <dd class="col-sm-8">{{ $category->id }}</dd>
                                
                                <dt class="col-sm-4">Nombre:</dt>
                                <dd class="col-sm-8">{{ $category->name }}</dd>
                                
                                <dt class="col-sm-4">Descripción:</dt>
                                <dd class="col-sm-8">{{ $category->description ?? $category->descripcion }}</dd>
                                
                                <dt class="col-sm-4">Artículos:</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-{{ $category->articles->count() > 0 ? 'success' : 'secondary' }}">
                                        {{ $category->articles->count() }}
                                    </span>
                                </dd>
                                
                                <dt class="col-sm-4">Estado:</dt>
                                <dd class="col-sm-8">
                                    @if($category->articles->count() > 0)
                                        <span class="badge bg-success">Activa</span>
                                    @else
                                        <span class="badge bg-warning">Vacía</span>
                                    @endif
                                </dd>
                                
                                <dt class="col-sm-4">Creada:</dt>
                                <dd class="col-sm-8">
                                    {{ $category->created_at->format('d/m/Y H:i:s') }}
                                    <small class="text-muted">({{ $category->created_at->diffForHumans() }})</small>
                                </dd>
                                
                                <dt class="col-sm-4">Modificada:</dt>
                                <dd class="col-sm-8">
                                    {{ $category->updated_at->format('d/m/Y H:i:s') }}
                                    <small class="text-muted">({{ $category->updated_at->diffForHumans() }})</small>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                Estadísticas
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($category->articles->count() > 0)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Artículo más reciente:</span>
                                        <strong>{{ $category->articles->sortByDesc('created_at')->first()->created_at->diffForHumans() }}</strong>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Artículo más antiguo:</span>
                                        <strong>{{ $category->articles->sortBy('created_at')->first()->created_at->diffForHumans() }}</strong>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Promedio de caracteres:</span>
                                        <strong>{{ number_format($category->articles->avg(function($article) { return strlen($article->content); })) }}</strong>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-chart-bar fa-3x mb-3"></i>
                                    <p>No hay estadísticas disponibles</p>
                                    <small>Esta categoría no tiene artículos asociados</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Artículos asociados --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-newspaper me-2"></i>
                        Artículos Asociados
                        <span class="badge bg-primary ms-2">{{ $category->articles->count() }}</span>
                    </h5>
                    @if($category->articles->count() > 0)
                        <a href="{{ route('articles.create') }}?category={{ $category->id }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus me-1"></i>
                            Nuevo Artículo
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($category->articles->count() > 0)
                        {{-- Filtros para artículos --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="searchArticles" 
                                           placeholder="Buscar artículos...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-2">
                                    <select class="form-select" id="sortArticles">
                                        <option value="newest">Más recientes</option>
                                        <option value="oldest">Más antiguos</option>
                                        <option value="title">Por título A-Z</option>
                                        <option value="author">Por autor</option>
                                    </select>
                                    <button class="btn btn-outline-secondary" onclick="clearArticleFilters()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Lista de artículos --}}
                        <div class="row" id="articlesContainer">
                            @foreach($category->articles->sortByDesc('created_at') as $article)
                                <div class="col-lg-6 mb-3 article-item" 
                                     data-title="{{ strtolower($article->title) }}"
                                     data-author="{{ strtolower($article->author) }}"
                                     data-date="{{ $article->created_at->timestamp }}">
                                    <div class="card h-100 border-start border-primary border-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-0">
                                                    <a href="{{ route('articles.show', $article) }}" 
                                                       class="text-decoration-none">
                                                        {{ $article->title }}
                                                    </a>
                                                </h6>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                            type="button" 
                                                            data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('articles.show', $article) }}">
                                                                <i class="fas fa-eye me-2"></i>Ver
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('articles.edit', $article) }}">
                                                                <i class="fas fa-edit me-2"></i>Editar
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#"
                                                               onclick="confirmDeleteArticle('{{ $article->id }}', '{{ $article->title }}')">
                                                                <i class="fas fa-trash me-2"></i>Eliminar
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                            <p class="card-text text-muted small mb-2">
                                                {{ Str::limit(strip_tags($article->content), 120) }}
                                            </p>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-muted me-1"></i>
                                                    <small class="text-muted">{{ $article->author }}</small>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-muted me-1"></i>
                                                    <small class="text-muted">{{ $article->created_at->format('d/m/Y') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="badge bg-light text-dark">
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $article->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('articles.show', $article) }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('articles.edit', $article) }}" 
                                                       class="btn btn-outline-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Paginación si hay muchos artículos --}}
                        @if($category->articles->count() > 12)
                            <div class="d-flex justify-content-center mt-4">
                                <nav>
                                    <div class="pagination-info">
                                        <small class="text-muted">
                                            Mostrando 12 de {{ $category->articles->count() }} artículos
                                        </small>
                                    </div>
                                </nav>
                            </div>
                        @endif
                    @else
                        {{-- Estado vacío --}}
                        <div class="text-center py-5">
                            <i class="fas fa-newspaper fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted mb-3">Esta categoría no tiene artículos</h5>
                            <p class="text-muted mb-4">
                                Los artículos que se asignen a esta categoría aparecerán aquí.
                            </p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('articles.create') }}?category={{ $category->id }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Crear Primer Artículo
                                </a>
                                <a href="{{ route('articles.index') }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-list me-1"></i>
                                    Ver Todos los Artículos
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Acciones rápidas --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-secondary">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-bolt me-2"></i>
                                Acciones Rápidas
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <a href="{{ route('categories.edit', $category) }}" 
                                       class="btn btn-warning w-100">
                                        <i class="fas fa-edit me-1"></i>
                                        Editar Categoría
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="{{ route('articles.create') }}?category={{ $category->id }}" 
                                       class="btn btn-success w-100">
                                        <i class="fas fa-plus me-1"></i>
                                        Nuevo Artículo
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="{{ route('categories.create') }}" 
                                       class="btn btn-info w-100">
                                        <i class="fas fa-copy me-1"></i>
                                        Duplicar Categoría
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    @if($category->articles->count() == 0)
                                        <button type="button" 
                                                class="btn btn-danger w-100"
                                                onclick="confirmDelete('{{ $category->id }}', '{{ $category->name }}', {{ $category->articles->count() }})">
                                            <i class="fas fa-trash me-1"></i>
                                            Eliminar
                                        </button>
                                    @else
                                        <button type="button" 
                                                class="btn btn-outline-danger w-100"
                                                disabled
                                                title="No se puede eliminar: tiene artículos asociados">
                                            <i class="fas fa-ban me-1"></i>
                                            No Eliminable
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de confirmación para eliminar categoría --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar la categoría <strong id="categoryName"></strong>?</p>
                <div id="warningMessage" class="alert alert-warning d-none">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Esta categoría tiene <strong id="articlesCount"></strong> artículo(s) asociado(s). 
                    No podrá eliminarse hasta que reasignes o elimines estos artículos.
                </div>
                <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-1"></i>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal de confirmación para eliminar artículo --}}
<div class="modal fade" id="deleteArticleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Eliminar Artículo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el artículo <strong id="articleTitle"></strong>?</p>
                <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteArticleForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Eliminar Artículo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Scripts específicos para esta vista --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const searchInput = document.getElementById('searchArticles');
    const sortSelect = document.getElementById('sortArticles');
    const articlesContainer = document.getElementById('articlesContainer');
    
    if (searchInput && articlesContainer) {
        searchInput.addEventListener('input', filterArticles);
    }
    
    if (sortSelect && articlesContainer) {
        sortSelect.addEventListener('change', sortArticles);
    }
    
    function filterArticles() {
        const searchTerm = searchInput.value.toLowerCase();
        const articles = articlesContainer.querySelectorAll('.article-item');
        
        articles.forEach(article => {
            const title = article.dataset.title;
            const author = article.dataset.author;
            const content = article.querySelector('.card-text').textContent.toLowerCase();
            
            const matches = title.includes(searchTerm) || 
                          author.includes(searchTerm) || 
                          content.includes(searchTerm);
            
            article.style.display = matches ? '' : 'none';
        });
    }
    
    function sortArticles() {
        const sortValue = sortSelect.value;
        const articles = Array.from(articlesContainer.querySelectorAll('.article-item'));
        
        articles.sort((a, b) => {
            switch(sortValue) {
                case 'newest':
                    return parseInt(b.dataset.date) - parseInt(a.dataset.date);
                case 'oldest':
                    return parseInt(a.dataset.date) - parseInt(b.dataset.date);
                case 'title':
                    return a.dataset.title.localeCompare(b.dataset.title);
                case 'author':
                    return a.dataset.author.localeCompare(b.dataset.author);
                default:
                    return 0;
            }
        });

        articles.forEach(article => {
            articlesContainer.appendChild(article);
        });
    }

    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert && alert.parentNode) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 500);
            }
        }, 5000);
    });
});

function confirmDelete(categoryId, categoryName, articlesCount) {
    document.getElementById('categoryName').textContent = categoryName;
    document.getElementById('articlesCount').textContent = articlesCount;
    document.getElementById('deleteForm').action = `/categories/${categoryId}`;

    const warningMessage = document.getElementById('warningMessage');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    
    if (articlesCount > 0) {
        warningMessage.classList.remove('d-none');
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-ban me-1"></i>No se puede eliminar';
    } else {
        warningMessage.classList.add('d-none');
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = '<i class="fas fa-trash me-1"></i>Eliminar';
    }

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function confirmDeleteArticle(articleId, articleTitle) {

    document.getElementById('articleTitle').textContent = articleTitle;
    document.getElementById('deleteArticleForm').action = `/articles/${articleId}`;

    const modal = new bootstrap.Modal(document.getElementById('deleteArticleModal'));
    modal.show();
}

function clearArticleFilters() {
    document.getElementById('searchArticles').value = '';
    document.getElementById('sortArticles').value = 'newest';

    const articles = document.querySelectorAll('.article-item');
    articles.forEach(article => {
        article.style.display = '';
    });

    const articlesContainer = document.getElementById('articlesContainer');
    const articlesList = Array.from(articles);
    articlesList.sort((a, b) => parseInt(b.dataset.date) - parseInt(a.dataset.date));
    articlesList.forEach(article => {
        articlesContainer.appendChild(article);
    });
}
</script>
@endpush