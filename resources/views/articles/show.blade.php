@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <div class="sidebar-header mb-4">
                    <img src="https://via.placeholder.com/40x40/4472C4/ffffff?text=USAP" alt="USAP" class="me-2">
                    <span class="fw-bold">USAP</span>
                </div>
                
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-2"></i>
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('articles.index') }}">
                            <i class="fas fa-newspaper me-2"></i>
                            Artículos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-briefcase me-2"></i>
                            Bolsa de Empleo
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-cog me-2"></i>
                            Configuraciones
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Ver artículo</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Volver
                        </a>
                        <a href="{{ route('articles.edit', $article) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i>
                            Editar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contenido del artículo -->
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-newspaper me-2"></i>
                                {{ $article->title }}
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <!-- Metadatos del artículo -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <i class="fas fa-user text-primary me-2"></i>
                                        <strong>Autor:</strong> {{ $article->author }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <i class="fas fa-folder text-primary me-2"></i>
                                        <strong>Categoría:</strong> 
                                        <span class="badge bg-secondary">{{ $article->category->name ?? 'Sin categoría' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <i class="fas fa-calendar text-primary me-2"></i>
                                        <strong>Fecha de creación:</strong> {{ $article->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        <strong>Última actualización:</strong> {{ $article->updated_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Separador -->
                            <hr class="my-4">

                            <!-- Contenido principal -->
                            <div class="article-content">
                                <h5 class="text-muted mb-3">
                                    <i class="fas fa-file-alt me-2"></i>
                                    Contenido del artículo
                                </h5>
                                <div class="content-text">
                                    {!! nl2br(e($article->content)) !!}
                                </div>
                            </div>

                            <!-- Estadísticas del artículo -->
                            <hr class="my-4">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="stat-item">
                                        <i class="fas fa-eye fa-2x text-info mb-2"></i>
                                        <h6 class="text-muted">Visualizaciones</h6>
                                        <p class="mb-0">{{ rand(50, 500) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-item">
                                        <i class="fas fa-heart fa-2x text-danger mb-2"></i>
                                        <h6 class="text-muted">Me gusta</h6>
                                        <p class="mb-0">{{ rand(5, 50) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-item">
                                        <i class="fas fa-share fa-2x text-success mb-2"></i>
                                        <h6 class="text-muted">Compartidas</h6>
                                        <p class="mb-0">{{ rand(1, 20) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    ID del artículo: #{{ $article->id }}
                                </div>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('articles.edit', $article) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i>
                                        Editar
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal">
                                        <i class="fas fa-trash me-1"></i>
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirmar eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el artículo <strong>"{{ $article->title }}"</strong>?</p>
                <p class="text-muted small">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Cancelar
                </button>
                <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Eliminar definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 48px 0 0;
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

.sidebar-header {
    display: flex;
    align-items: center;
    padding: 0 1rem;
}

.sidebar .nav-link {
    color: #333;
    padding: 0.75rem 1rem;
    border-radius: 0;
}

.sidebar .nav-link:hover {
    color: #007bff;
    background-color: rgba(0, 123, 255, 0.1);
}

.sidebar .nav-link.active {
    color: #007bff;
    background-color: rgba(0, 123, 255, 0.1);
    border-right: 3px solid #007bff;
}

main {
    margin-left: 240px;
}

@media (max-width: 767.98px) {
    .sidebar {
        position: relative;
        height: auto;
    }
    
    main {
        margin-left: 0;
    }
}

.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.5rem;
    overflow: hidden;
}

.card-header {
    background-color: #4472C4 !important;
    border-bottom: 1px solid #e3e6f0;
    padding: 1.25rem;
}

.card-body {
    padding: 2rem;
}

.card-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #e3e6f0;
    padding: 1rem 1.25rem;
}

.info-item {
    margin-bottom: 1rem;
    padding: 0.75rem;
    background-color: #f8f9fa;
    border-radius: 0.375rem;
    border-left: 4px solid #4472C4;
}

.article-content {
    background-color: #fff;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.content-text {
    font-size: 1rem;
    line-height: 1.6;
    color: #333;
    text-align: justify;
}

.stat-item {
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    transition: transform 0.2s ease;
}

.stat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
}

.btn-primary {
    background-color: #4472C4;
    border-color: #4472C4;
}

.btn-primary:hover {
    background-color: #365a9e;
    border-color: #365a9e;
}

.btn-outline-primary {
    color: #4472C4;
    border-color: #4472C4;
}

.btn-outline-primary:hover {
    background-color: #4472C4;
    border-color: #4472C4;
}

.btn-outline-danger {
    color: #e74a3b;
    border-color: #e74a3b;
}

.btn-outline-danger:hover {
    background-color: #e74a3b;
    border-color: #e74a3b;
}

.badge {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}

.modal-content {
    border-radius: 0.5rem;
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header {
    border-bottom: 1px solid #e3e6f0;
    padding: 1.25rem;
}

.modal-body {
    padding: 1.25rem;
}

.modal-footer {
    border-top: 1px solid #e3e6f0;
    padding: 1.25rem;
}

hr {
    border-color: #e3e6f0;
    margin: 1.5rem 0;
}
</style>
@endsection