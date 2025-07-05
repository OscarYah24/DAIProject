@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            {{-- Header de la página --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-0">
                        <i class="fas fa-edit me-2 text-warning"></i>
                        Editar Categoría
                    </h2>
                    <p class="text-muted mb-0">Modificar información de "{{ $category->name }}"</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-info">
                        <i class="fas fa-eye me-1"></i>
                        Ver Categoría
                    </a>
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
                    <li class="breadcrumb-item">
                        <a href="{{ route('categories.show', $category) }}">{{ Str::limit($category->name, 20) }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Editar</li>
                </ol>
            </nav>

            {{-- Alertas y mensajes de error --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Información de la categoría actual --}}
            <div class="card border-info mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Información Actual
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Nombre:</strong> {{ $category->name }}
                            </p>
                            <p class="mb-2">
                                <strong>Descripción:</strong> {{ $category->description ?? $category->descripcion }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Artículos asociados:</strong> 
                                <span class="badge bg-primary">{{ $category->articles->count() }}</span>
                            </p>
                            <p class="mb-0">
                                <strong>Creada:</strong> {{ $category->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Formulario de edición --}}
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Editar Información
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.update', $category) }}" 
                          method="POST" 
                          id="categoryEditForm" 
                          data-autosave="true">
                        @csrf
                        @method('PUT')
                        
                        {{-- Campo Nombre --}}
                        <div class="mb-4">
                            <label for="name" class="form-label required">
                                <i class="fas fa-tag me-1"></i>
                                Nombre de la Categoría
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-tag text-warning"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $category->name) }}"
                                       placeholder="Ej: Tecnología, Deportes, Educación..."
                                       maxlength="120"
                                       required
                                       autocomplete="off">
                                <div class="input-group-text">
                                    <span id="nameCounter" class="text-muted small">0/120</span>
                                </div>
                            </div>
                            
                            {{-- Mensajes de error para nombre --}}
                            @error('name')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            
                            {{-- Indicador de disponibilidad del nombre --}}
                            <div id="nameAvailability" class="form-text d-none">
                                <i class="fas fa-spinner fa-spin me-1"></i>
                                Verificando disponibilidad...
                            </div>
                            
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                El nombre debe ser único y descriptivo. Máximo 120 caracteres.
                            </div>
                        </div>

                        {{-- Campo Descripción --}}
                        <div class="mb-4">
                            <label for="description" class="form-label required">
                                <i class="fas fa-align-left me-1"></i>
                                Descripción
                            </label>
                            <div class="input-group">
                                <span class="input-group-text align-items-start pt-2">
                                    <i class="fas fa-align-left text-warning"></i>
                                </span>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4"
                                          placeholder="Describe el tipo de contenido que incluirá esta categoría..."
                                          maxlength="120"
                                          required>{{ old('description', $category->description ?? $category->descripcion) }}</textarea>
                            </div>
                            
                            {{-- Contador de caracteres para descripción --}}
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <div>
                                    {{-- Mensajes de error para descripción --}}
                                    @error('description')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    
                                    @if(!$errors->has('description'))
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Proporciona una descripción clara del propósito de esta categoría.
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <span id="descriptionCounter" class="text-muted small">0/120</span>
                                </div>
                            </div>
                        </div>

                        {{-- Comparación: Antes vs Después --}}
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-exchange-alt me-1"></i>
                                Comparación de Cambios
                            </label>
                            <div class="row">
                                {{-- Antes --}}
                                <div class="col-md-6">
                                    <div class="card border-secondary">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 text-muted">
                                                <i class="fas fa-history me-1"></i>
                                                Antes
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-tag text-white"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $category->name }}</h6>
                                                    <p class="mb-0 text-muted small">
                                                        {{ Str::limit($category->description ?? $category->descripcion, 60) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Después --}}
                                <div class="col-md-6">
                                    <div class="card border-warning">
                                        <div class="card-header bg-warning text-dark">
                                            <h6 class="mb-0">
                                                <i class="fas fa-edit me-1"></i>
                                                Después (Vista Previa)
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-tag text-dark"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1" id="previewName">{{ $category->name }}</h6>
                                                    <p class="mb-0 text-muted small" id="previewDescription">
                                                        {{ $category->description ?? $category->descripcion }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <span class="badge bg-warning text-dark" id="changeIndicator" style="display: none;">
                                                        Modificado
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Artículos asociados (si los hay) --}}
                        @if($category->articles->count() > 0)
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-newspaper me-1"></i>
                                    Artículos Asociados ({{ $category->articles->count() }})
                                </label>
                                <div class="card border-info">
                                    <div class="card-body">
                                        <div class="alert alert-info mb-3">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Esta categoría tiene {{ $category->articles->count() }} artículo(s) asociado(s). 
                                            Los cambios se aplicarán automáticamente a todos estos artículos.
                                        </div>
                                        
                                        <div class="row">
                                            @foreach($category->articles->take(6) as $article)
                                                <div class="col-md-6 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-newspaper text-primary me-2"></i>
                                                        <span class="text-truncate">{{ $article->title }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        @if($category->articles->count() > 6)
                                            <div class="text-center mt-2">
                                                <small class="text-muted">
                                                    Y {{ $category->articles->count() - 6 }} artículo(s) más...
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Botones de acción --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                {{-- Indicador de auto-guardado --}}
                                <small id="autosaveIndicator" class="text-muted d-none">
                                    <i class="fas fa-save me-1"></i>
                                    Cambios guardados automáticamente
                                </small>
                            </div>
                            <div class="btn-group" role="group">
                                <a href="{{ route('categories.show', $category) }}" 
                                   class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Cancelar
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-warning"
                                        onclick="resetForm()">
                                    <i class="fas fa-undo me-1"></i>
                                    Restablecer
                                </button>
                                <button type="submit" 
                                        class="btn btn-warning"
                                        id="submitBtn">
                                    <i class="fas fa-save me-1"></i>
                                    Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Información de auditoría --}}
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card border-secondary">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-history me-2"></i>
                                Información de Auditoría
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block">Creada:</small>
                                    <strong>{{ $category->created_at->format('d/m/Y H:i') }}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Modificada:</small>
                                    <strong>{{ $category->updated_at->format('d/m/Y H:i') }}</strong>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <small class="text-muted d-block">Tiempo transcurrido:</small>
                                    <strong>{{ $category->created_at->diffForHumans() }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Advertencias
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @if($category->articles->count() > 0)
                                    <li class="mb-2">
                                        <i class="fas fa-info text-primary me-2"></i>
                                        Los cambios afectarán {{ $category->articles->count() }} artículo(s)
                                    </li>
                                @endif
                                <li class="mb-2">
                                    <i class="fas fa-info text-primary me-2"></i>
                                    El nombre debe seguir siendo único
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-info text-primary me-2"></i>
                                    Los cambios se guardan automáticamente
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-info text-primary me-2"></i>
                                    Se mantiene el historial de modificaciones
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Estilos específicos para esta vista --}}
@push('styles')
<style>
.required::after {
    content: " *";
    color: #dc3545;
}

.card-header.bg-warning {
    background-color: #ffc107 !important;
}

.comparison-card {
    transition: all 0.3s ease;
}

.comparison-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

#nameAvailability.available {
    color: #198754;
}

#nameAvailability.unavailable {
    color: #dc3545;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.form-control:focus + .input-group-text {
    border-color: #86b7fe;
}

.change-indicator {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

@media (max-width: 768px) {
    .btn-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .btn-group .btn {
        width: 100%;
    }
}
</style>
@endpush

{{-- Scripts específicos para esta vista --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const nameCounter = document.getElementById('nameCounter');
    const descriptionCounter = document.getElementById('descriptionCounter');
    const previewName = document.getElementById('previewName');
    const previewDescription = document.getElementById('previewDescription');
    const nameAvailability = document.getElementById('nameAvailability');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('categoryEditForm');
    const changeIndicator = document.getElementById('changeIndicator');

    const originalName = '{{ $category->name }}';
    const originalDescription = '{{ $category->description ?? $category->descripcion }}';

    function updateCounter(input, counter, maxLength) {
        const currentLength = input.value.length;
        counter.textContent = `${currentLength}/${maxLength}`;

        if (currentLength > maxLength * 0.9) {
            counter.className = 'text-danger small';
        } else if (currentLength > maxLength * 0.7) {
            counter.className = 'text-warning small';
        } else {
            counter.className = 'text-muted small';
        }
    }
    
    nameInput.addEventListener('input', function() {
        updateCounter(this, nameCounter, 120);
        updatePreview();
        checkNameAvailability();
        checkForChanges();
    });
    
    descriptionInput.addEventListener('input', function() {
        updateCounter(this, descriptionCounter, 120);
        updatePreview();
        checkForChanges();
    });

    function updatePreview() {
        const nameValue = nameInput.value.trim();
        const descriptionValue = descriptionInput.value.trim();
        
        previewName.textContent = nameValue || originalName;
        previewDescription.textContent = descriptionValue || originalDescription;
    }
    
    function checkForChanges() {
        const nameChanged = nameInput.value.trim() !== originalName;
        const descriptionChanged = descriptionInput.value.trim() !== originalDescription;
        const hasChanges = nameChanged || descriptionChanged;
        
        if (hasChanges) {
            changeIndicator.style.display = 'inline-block';
            changeIndicator.classList.add('change-indicator');
            submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Guardar Cambios';
            submitBtn.classList.remove('btn-warning');
            submitBtn.classList.add('btn-success');
        } else {
            changeIndicator.style.display = 'none';
            changeIndicator.classList.remove('change-indicator');
            submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Guardar Cambios';
            submitBtn.classList.remove('btn-success');
            submitBtn.classList.add('btn-warning');
        }
    }

    let nameCheckTimeout;
    
    function checkNameAvailability() {
        const name = nameInput.value.trim();

        if (name === originalName) {
            nameAvailability.classList.add('d-none');
            submitBtn.disabled = false;
            return;
        }
        
        if (name.length < 2) {
            nameAvailability.classList.add('d-none');
            return;
        }
        
        clearTimeout(nameCheckTimeout);
        nameCheckTimeout = setTimeout(() => {
            nameAvailability.classList.remove('d-none');
            nameAvailability.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Verificando disponibilidad...';
            
            fetch('/api/categories/validate-name', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    name: name,
                    category_id: '{{ $category->id }}'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    nameAvailability.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>Nombre disponible';
                    nameAvailability.className = 'form-text available';
                    submitBtn.disabled = false;
                } else {
                    nameAvailability.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i>' + data.message;
                    nameAvailability.className = 'form-text unavailable';
                    submitBtn.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                nameAvailability.classList.add('d-none');
            });
        }, 500);
    }

    let saveTimeout;
    const autosaveIndicator = document.getElementById('autosaveIndicator');
    
    function autoSave() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(() => {
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => data[key] = value);
            
            localStorage.setItem('category_edit_{{ $category->id }}', JSON.stringify(data));
            showAutosaveIndicator();
        }, 2000);
    }
    
    function showAutosaveIndicator() {
        autosaveIndicator.classList.remove('d-none');
        setTimeout(() => {
            autosaveIndicator.classList.add('d-none');
        }, 3000);
    }
    
    nameInput.addEventListener('input', autoSave);
    descriptionInput.addEventListener('input', autoSave);

    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';

        localStorage.removeItem('category_edit_{{ $category->id }}');
    });

    updateCounter(nameInput, nameCounter, 120);
    updateCounter(descriptionInput, descriptionCounter, 120);
    updatePreview();
    checkForChanges();
});

function resetForm() {
    document.getElementById('name').value = '{{ $category->name }}';
    document.getElementById('description').value = '{{ $category->description ?? $category->descripcion }}';

    document.getElementById('nameCounter').textContent = '{{ strlen($category->name) }}/120';
    document.getElementById('descriptionCounter').textContent = '{{ strlen($category->description ?? $category->descripcion) }}/120';

    document.getElementById('nameAvailability').classList.add('d-none');

    document.getElementById('previewName').textContent = '{{ $category->name }}';
    document.getElementById('previewDescription').textContent = '{{ $category->description ?? $category->descripcion }}';

    document.getElementById('changeIndicator').style.display = 'none';

    localStorage.removeItem('category_edit_{{ $category->id }}');

    document.getElementById('name').focus();
}
</script>
@endpush