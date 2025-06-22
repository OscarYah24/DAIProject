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
                <h1 class="h2">Artículos</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="{{ route('articles.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Crear
                        </a>
                    </div>
                </div>
            </div>

            <!-- Alertas -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Controles de la tabla -->
            <div class="row mb-3">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <label for="entries" class="form-label me-2 mb-0">Show</label>
                        <select class="form-select form-select-sm" id="entries" style="width: auto;">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="ms-2">entries</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="d-flex justify-content-end">
                        <label for="search" class="form-label me-2 mb-0">Search:</label>
                        <input type="search" class="form-control form-control-sm" id="search" style="width: auto;" placeholder="">
                    </div>
                </div>
            </div>

            <!-- Tabla de artículos -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">
                                Título
                                <i class="fas fa-sort ms-1 text-muted"></i>
                            </th>
                            <th scope="col">Autor</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                        <tr>
                            <td>{{ $article->title }}</td>
                            <td>{{ $article->author }}</td>
                            <td>
                                <a href="{{ route('articles.edit', $article) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit me-1"></i>
                                    Editar artículo
                                </a>
                                <a href="{{ route('articles.show', $article) }}" 
                                   class="btn btn-info btn-sm ms-1">
                                    <i class="fas fa-eye me-1"></i>
                                    Ver
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No hay artículos disponibles</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Información de paginación y controles -->
            <div class="row">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info text-muted">
                        Showing 1 to {{ $articles->count() }} of {{ $articles->count() }} entries
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="d-flex justify-content-end">
                        <nav aria-label="Navegación de páginas">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item disabled">
                                    <span class="page-link">Next</span>
                                </li>
                            </ul>
                        </nav>
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

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
}

.btn-primary {
    background-color: #4472C4;
    border-color: #4472C4;
}

.btn-primary:hover {
    background-color: #365a9e;
    border-color: #365a9e;
}

.alert {
    border-radius: 0.5rem;
    border: none;
}

.form-control:focus,
.form-select:focus {
    border-color: #4472C4;
    box-shadow: 0 0 0 0.2rem rgba(68, 114, 196, 0.25);
}
</style>
@endsection