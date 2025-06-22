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
                <h1 class="h2">Crear artículo</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Volver
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulario de creación -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <form action="{{ route('articles.store') }}" method="POST">
                                @csrf
                                
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
                                           value="{{ old('title') }}" 
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
                                              required>{{ old('content') }}</textarea>
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
                                           value="{{ old('author') }}" 
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
                                        <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>
                                            Completa este campo
                                        </option>
                                        @forelse($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
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

                                <!-- Nota informativa -->
                                <div class="alert alert-info d-flex align-items-center mb-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <small>Recuerda siempre guardar los cambios.</small>
                                </div>

                                <!-- Botones de acción -->
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Crear
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
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

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
    border-radius: 0.375rem;
    border: 1px solid transparent;
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
</style>
@endsection