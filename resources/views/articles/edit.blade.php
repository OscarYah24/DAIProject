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
                <h1 class="h2">Editar artículo</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Volver
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulario de edición -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <form action="{{ route('articles.update', $article) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <!-- Campo: Nombre del artículo -->
                                <div class="mb-4">
                                    <label for="title" class="form-label fw-medium">
                                        Nombre del artículo 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $article->title) }}" 
                                           placeholder="Ingrese el título del artículo"
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Campo: Contenido -->
                                <div class="mb-4">
                                    <label for="content" class="form-label fw-medium">
                                        Contenido 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" 
                                              name="content" 
                                              rows="6" 
                                              placeholder="Escriba el contenido del artículo"
                                              required>{{ old('content', $article->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Campo: Autor -->
                                <div class="mb-4">
                                    <label for="author" class="form-label fw-medium">
                                        Autor 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('author') is-invalid @enderror" 
                                           id="author" 
                                           name="author" 
                                           value="{{ old('author', $article->author) }}" 
                                           placeholder="Nombre del autor"
                                           required>
                                    @error('author')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Campo: Categoría -->
                                <div class="mb-4">
                                    <label for="category_id" class="form-label fw-medium">
                                        Categoría 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" 
                                            name="category_id" 
                                            required>
                                        <option value="" disabled>Completa este campo</option>
                                        @forelse($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @empty
                                            <option value="" disabled>No hay categorías disponibles</option>
                                        @endforelse
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Información del artículo -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="alert alert-light">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                <strong>Creado:</strong> {{ $article->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-light">
                                            <small class="text-muted">
                                                <i class="fas fa-edit me-1"></i>
                                                <strong>Última modificación:</strong> {{ $article->updated_at->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nota informativa -->
                                <div class="alert alert-info d-flex align-items-center mb-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <small>Recuerda siempre guardar los cambios.</small>
                                </div>

                                <!-- Botones de acción -->
                                <div class="d-flex justify-content-between">
                                    <button type="button" 
                                            class="btn btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal">
                                        <i class="fas fa-trash me-1"></i>
                                        Eliminar
                                    </button>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-1"></i>
                                            Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>
                                            Actualizar
                                        </button>
                                    </div>
                                </div>
                            </form>
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
}

.form-label {
    color: #5a5c69;
    margin-bottom: 0.5rem;
}

.form-control,
.form-select {
    border: 1px solid #d1d3e2;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    padding: 0.75rem;
}

.form-control:focus,
.form-select:focus {
    border-color: #4472C4;
    box-shadow: 0 0 0 0.2rem rgba(68, 114, 196, 0.25);
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

.btn-secondary {
    background-color: #858796;
    border-color: #858796;
}

.btn-secondary:hover {
    background-color: #717384;
    border-color: #717384;
}

.btn-danger {
    background-color: #e74a3b;
    border-color: #e74a3b;
}

.btn-danger:hover {
    background-color: #c0392b;
    border-color: #c0392b;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
    border-radius: 0.375rem;
    border: 1px solid transparent;
}

.alert-light {
    background-color: #fefefe;
    border-color: #fdfdfe;
    color: #818182;
    border-radius: 0.375rem;
    border: 1px solid #e9ecef;
}

.is-invalid {
    border-color: #e74a3b;
}

.invalid-feedback {
    color: #e74a3b;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.gap-2 {
    gap: 0.5rem;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
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
</style>
@endsection