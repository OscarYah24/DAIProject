@extends('layouts.app')

@section('title', 'Gestión de Categorías')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            {{-- Header de la página --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-0">
                        <i class="fas fa-tags me-2 text-primary"></i>
                        Gestión de Categorías
                    </h2>
                    <p class="text-muted mb-0">Administra las categorías del sistema</p>
                </div>
                <div>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Nueva Categoría
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
                    <li class="breadcrumb-item active" aria-current="page">Categorías</li>
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

            {{-- Estadísticas rápidas --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <i class="fas fa-tags fa-2x text-primary mb-2"></i>
                            <h5 class="card-title">{{ $categories->count() }}</h5>
                            <p class="card-text text-muted">Total Categorías</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-newspaper fa-2x text-success mb-2"></i>
                            <h5 class="card-title">{{ $categories->where('articles_count', '>', 0)->count() }}</h5>
                            <p class="card-text text-muted">Con Artículos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-warning">
                        <div class="card-body text-center">
                            <i class="fas fa-inbox fa-2x text-warning mb-2"></i>
                            <h5 class="card-title">{{ $categories->where('articles_count', 0)->count() }}</h5>
                            <p class="card-text text-muted">Sin Artículos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-info">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar fa-2x text-info mb-2"></i>
                            <h5 class="card-title">{{ $categories->where('created_at', '>=', now()->subDays(7))->count() }}</h5>
                            <p class="card-text text-muted">Nuevas (7 días)</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Buscador --}}
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       id="searchCategories" 
                                       placeholder="Buscar categorías por nombre o descripción...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2">
                                <select class="form-select" id="filterByArticles">
                                    <option value="">Todas las categorías</option>
                                    <option value="with">Con artículos</option>
                                    <option value="without">Sin artículos</option>
                                </select>
                                <button class="btn btn-outline-secondary" onclick="clearFilters()">
                                    <i class="fas fa-times"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla de categorías --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Lista de Categorías
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="categoriesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 5%;">#</th>
                                        <th scope="col" style="width: 25%;">
                                            <i class="fas fa-tag me-1"></i>Nombre
                                        </th>
                                        <th scope="col" style="width: 35%;">
                                            <i class="fas fa-align-left me-1"></i>Descripción
                                        </th>
                                        <th scope="col" style="width: 15%;" class="text-center">
                                            <i class="fas fa-newspaper me-1"></i>Artículos
                                        </th>
                                        <th scope="col" style="width: 15%;" class="text-center">
                                            <i class="fas fa-calendar me-1"></i>Creada
                                        </th>
                                        <th scope="col" style="width: 15%;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $index => $category)
                                        <tr data-category-id="{{ $category->id }}" 
                                            data-articles-count="{{ $category->articles_count }}">
                                            <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 32px; height: 32px;">
                                                        <i class="fas fa-tag text-white"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $category->name }}</strong>
                                                        @if($category->articles_count > 0)
                                                            <span class="badge bg-success ms-1">Activa</span>
                                                        @else
                                                            <span class="badge bg-secondary ms-1">Vacía</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    {{ Str::limit($category->description ?? $category->descripcion, 80) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($category->articles_count > 0)
                                                    <a href="{{ route('categories.show', $category) }}" 
                                                       class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-eye me-1"></i>
                                                        {{ $category->articles_count }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">
                                                        <i class="fas fa-minus"></i> 0
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <small class="text-muted">
                                                    {{ $category->created_at->format('d/m/Y') }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    {{-- Ver --}}
                                                    <a href="{{ route('categories.show', $category) }}" 
                                                       class="btn btn-sm btn-outline-info"
                                                       title="Ver categoría">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    {{-- Editar --}}
                                                    <a href="{{ route('categories.edit', $category) }}" 
                                                       class="btn btn-sm btn-outline-warning"
                                                       title="Editar categoría">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    {{-- Eliminar --}}
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Eliminar categoría"
                                                            onclick="confirmDelete('{{ $category->id }}', '{{ $category->name }}', {{ $category->articles_count }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        {{-- Estado vacío --}}
                        <div class="text-center py-5">
                            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay categorías registradas</h5>
                            <p class="text-muted">Comienza creando tu primera categoría para organizar los artículos.</p>
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Crear Primera Categoría
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de confirmación para eliminar --}}
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
@endsection

{{-- Scripts específicos para esta vista --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const searchInput = document.getElementById('searchCategories');
    const filterSelect = document.getElementById('filterByArticles');
    const table = document.getElementById('categoriesTable');
    
    if (searchInput && table) {
        searchInput.addEventListener('input', filterTable);
    }
    
    if (filterSelect && table) {
        filterSelect.addEventListener('change', filterTable);
    }
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const filterValue = filterSelect.value;
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        Array.from(rows).forEach(row => {
            const name = row.cells[1].textContent.toLowerCase();
            const description = row.cells[2].textContent.toLowerCase();
            const articlesCount = parseInt(row.dataset.articlesCount);

            const matchesSearch = name.includes(searchTerm) || description.includes(searchTerm);

            let matchesFilter = true;
            if (filterValue === 'with') {
                matchesFilter = articlesCount > 0;
            } else if (filterValue === 'without') {
                matchesFilter = articlesCount === 0;
            }

            row.style.display = (matchesSearch && matchesFilter) ? '' : 'none';
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

function clearFilters() {
    document.getElementById('searchCategories').value = '';
    document.getElementById('filterByArticles').value = '';

    const rows = document.getElementById('categoriesTable').getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    Array.from(rows).forEach(row => {
        row.style.display = '';
    });
}
</script>
@endpush