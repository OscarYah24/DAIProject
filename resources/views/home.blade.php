@extends('layouts.app')

@section('title', 'Dashboard - Panel Administrativo')

{{-- ARCHIVO: resources/views/home.blade.php --}}
{{-- NOTA: Este archivo reemplaza completamente el contenido de home.blade.php --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            
            {{-- Header del Dashboard --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-0">
                        <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                        Panel Administrativo
                    </h2>
                    <p class="text-muted mb-0">Gestiona el contenido del sistema universitario</p>
                </div>
                <div>
                    @auth
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Bienvenido,</small>
                                <strong>{{ Auth::user()->name }}</strong>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>

            {{-- Alertas y mensajes --}}
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Estadísticas rápidas --}}
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-primary h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-newspaper fa-3x text-primary mb-3"></i>
                            <h4 class="card-title">{{ App\Models\Article::count() }}</h4>
                            <p class="card-text text-muted">Total Artículos</p>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-primary" style="width: 85%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-success h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-tags fa-3x text-success mb-3"></i>
                            <h4 class="card-title">{{ App\Models\Category::count() }}</h4>
                            <p class="card-text text-muted">Total Categorías</p>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-success" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-info h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user fa-3x text-info mb-3"></i>
                            <h4 class="card-title">{{ App\Models\User::count() }}</h4>
                            <p class="card-text text-muted">Usuarios Registrados</p>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-info" style="width: 45%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-warning h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-inbox fa-3x text-warning mb-3"></i>
                            <h4 class="card-title">{{ App\Models\Category::doesntHave('articles')->count() }}</h4>
                            <p class="card-text text-muted">Categorías Vacías</p>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-warning" style="width: 25%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Módulos principales --}}
            <div class="row mb-4">
                {{-- Módulo de Artículos --}}
                <div class="col-lg-6 mb-4">
                    <div class="card border-primary h-100">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-newspaper me-2"></i>
                                    Gestión de Artículos
                                </h5>
                                <span class="badge bg-light text-dark">{{ App\Models\Article::count() }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                Administra los artículos del sistema universitario. Crea, edita y organiza 
                                el contenido para mantener la información actualizada.
                            </p>
                            
                            {{-- Estadísticas de artículos --}}
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <div class="border-end">
                                        <strong class="d-block">{{ App\Models\Article::whereDate('created_at', today())->count() }}</strong>
                                        <small class="text-muted">Hoy</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <strong class="d-block">{{ App\Models\Article::whereDate('created_at', '>=', now()->subDays(7))->count() }}</strong>
                                        <small class="text-muted">Esta semana</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <strong class="d-block">{{ App\Models\Article::whereDate('created_at', '>=', now()->subDays(30))->count() }}</strong>
                                    <small class="text-muted">Este mes</small>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('articles.index') }}" class="btn btn-primary">
                                    <i class="fas fa-list me-1"></i>
                                    Ver Todos los Artículos
                                </a>
                                <a href="{{ route('articles.create') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Nuevo Artículo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Módulo de Categorías --}}
                <div class="col-lg-6 mb-4">
                    <div class="card border-success h-100">
                        <div class="card-header bg-success text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-tags me-2"></i>
                                    Gestión de Categorías
                                </h5>
                                <span class="badge bg-light text-dark">{{ App\Models\Category::count() }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                Organiza y administra las categorías del sistema. Crea nuevas categorías 
                                para clasificar mejor el contenido y facilitar la navegación.
                            </p>
                            
                            {{-- Estadísticas de categorías --}}
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <div class="border-end">
                                        <strong class="d-block">{{ App\Models\Category::has('articles')->count() }}</strong>
                                        <small class="text-muted">Con artículos</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <strong class="d-block">{{ App\Models\Category::doesntHave('articles')->count() }}</strong>
                                        <small class="text-muted">Vacías</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <strong class="d-block">{{ App\Models\Category::whereDate('created_at', '>=', now()->subDays(7))->count() }}</strong>
                                    <small class="text-muted">Nuevas</small>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('categories.index') }}" class="btn btn-success">
                                    <i class="fas fa-list me-1"></i>
                                    Ver Todas las Categorías
                                </a>
                                <a href="{{ route('categories.create') }}" class="btn btn-outline-success">
                                    <i class="fas fa-plus me-1"></i>
                                    Nueva Categoría
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actividad reciente --}}
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clock me-2"></i>
                                Actividad Reciente
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $recentArticles = App\Models\Article::with('category')->latest()->take(5)->get();
                                $recentCategories = App\Models\Category::latest()->take(3)->get();
                            @endphp
                            
                            @if($recentArticles->count() > 0 || $recentCategories->count() > 0)
                                <div class="timeline">
                                    {{-- Artículos recientes --}}
                                    @foreach($recentArticles as $article)
                                        <div class="timeline-item mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-newspaper text-white"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="mb-1">
                                                                <a href="{{ route('articles.show', $article) }}" 
                                                                   class="text-decoration-none">
                                                                    {{ Str::limit($article->title, 50) }}
                                                                </a>
                                                            </h6>
                                                            <small class="text-muted">
                                                                <i class="fas fa-user me-1"></i>{{ $article->author }} • 
                                                                <i class="fas fa-tag me-1"></i>{{ $article->category->name ?? 'Sin categoría' }} • 
                                                                {{ $article->created_at->diffForHumans() }}
                                                            </small>
                                                        </div>
                                                        <span class="badge bg-primary">Artículo</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    {{-- Categorías recientes --}}
                                    @foreach($recentCategories as $category)
                                        <div class="timeline-item mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-tag text-white"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="mb-1">
                                                                <a href="{{ route('categories.show', $category) }}" 
                                                                   class="text-decoration-none">
                                                                    {{ $category->name }}
                                                                </a>
                                                            </h6>
                                                            <small class="text-muted">
                                                                <i class="fas fa-align-left me-1"></i>{{ Str::limit($category->description ?? $category->descripcion, 40) }} • 
                                                                {{ $category->created_at->diffForHumans() }}
                                                            </small>
                                                        </div>
                                                        <span class="badge bg-success">Categoría</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-clock fa-3x mb-3"></i>
                                    <p>No hay actividad reciente para mostrar</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- Panel de acciones rápidas --}}
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-bolt me-2"></i>
                                Acciones Rápidas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('articles.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>
                                    Nuevo Artículo
                                </a>
                                <a href="{{ route('categories.create') }}" class="btn btn-success">
                                    <i class="fas fa-tag me-2"></i>
                                    Nueva Categoría
                                </a>
                                <hr>
                                <a href="{{ route('articles.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-newspaper me-2"></i>
                                    Gestionar Artículos
                                </a>
                                <a href="{{ route('categories.index') }}" class="btn btn-outline-success">
                                    <i class="fas fa-tags me-2"></i>
                                    Gestionar Categorías
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Resumen de categorías más utilizadas --}}
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-chart-pie me-2"></i>
                                Categorías Más Utilizadas
                            </h6>
                        </div>
                        <div class="card-body">
                            @php
                                $topCategories = App\Models\Category::withCount('articles')
                                               ->orderBy('articles_count', 'desc')
                                               ->take(5)
                                               ->get();
                            @endphp
                            
                            @if($topCategories->count() > 0)
                                @foreach($topCategories as $category)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-tag text-success me-2"></i>
                                            <span class="small">{{ Str::limit($category->name, 20) }}</span>
                                        </div>
                                        <span class="badge bg-light text-dark">{{ $category->articles_count }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-chart-pie fa-2x mb-2"></i>
                                    <p class="small mb-0">No hay datos disponibles</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Módulos adicionales (futuras expansiones) --}}
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card border-info h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-briefcase fa-3x text-info mb-3"></i>
                            <h5 class="card-title">Bolsa de Empleo</h5>
                            <p class="card-text">Gestiona las ofertas laborales disponibles</p>
                            <a href="#" class="btn btn-info disabled">
                                <i class="fas fa-clock me-1"></i>
                                Próximamente
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <div class="card border-warning h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar fa-3x text-warning mb-3"></i>
                            <h5 class="card-title">Eventos</h5>
                            <p class="card-text">Administra eventos y actividades universitarias</p>
                            <a href="#" class="btn btn-warning disabled">
                                <i class="fas fa-clock me-1"></i>
                                Próximamente
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <div class="card border-secondary h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-cog fa-3x text-secondary mb-3"></i>
                            <h5 class="card-title">Configuraciones</h5>
                            <p class="card-text">Ajustes generales del sistema</p>
                            <a href="#" class="btn btn-secondary disabled">
                                <i class="fas fa-clock me-1"></i>
                                Próximamente
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Información del sistema --}}
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h6 class="text-muted">Sistema USAP</h6>
                            <p class="mb-0">Desarrollo de Aplicaciones en Internet</p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Última Actividad</h6>
                            <p class="mb-0">{{ Auth::user()->updated_at->diffForHumans() ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Artículos Totales</h6>
                            <p class="mb-0">{{ App\Models\Article::count() }} publicados</p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Categorías Activas</h6>
                            <p class="mb-0">{{ App\Models\Category::has('articles')->count() }} en uso</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Estilos específicos para el dashboard --}}
@push('styles')
<style>
.timeline-item {
    border-left: 2px solid #e9ecef;
    padding-left: 1rem;
    margin-left: 20px;
    position: relative;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -6px;
    top: 50%;
    transform: translateY(-50%);
    width: 10px;
    height: 10px;
    background-color: #6c757d;
    border-radius: 50%;
}

.timeline-item:last-child {
    border-left: none;
}

.card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.progress {
    background-color: rgba(255,255,255,0.2);
}

.badge {
    font-size: 0.75em;
}
</style>
@endpush

{{-- Scripts específicos para el dashboard --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh de estadísticas cada 5 minutos
    setInterval(function() {
        // Aquí se puede implementar AJAX para actualizar estadísticas
        console.log('Actualizando estadísticas...');
    }, 300000);
    
    // Auto-dismiss de alertas
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
</script>
@endpush