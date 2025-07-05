@extends('layouts.app')

@section('title', 'Nueva Categoría')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            {{-- Header de la página --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-0">
                        <i class="fas fa-plus-circle me-2 text-success"></i>
                        Nueva Categoría
                    </h2>
                    <p class="text-muted mb-0">Crear una nueva categoría para organizar los artículos</p>
                </div>
                <div>
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
                    <li class="breadcrumb-item active" aria-current="page">Nueva Categoría</li>
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

            {{-- Formulario principal --}}
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Información de la Categoría
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" 
                          method="POST" 
                          id="categoryForm" 
                          data-autosave="true">
                        @csrf
                        
                        {{-- Campo Nombre --}}
                        <div class="mb-4">
                            <label for="name" class="form-label required">
                                <i class="fas fa-tag me-1"></i>
                                Nombre de la Categoría
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-tag text-primary"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}"
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
                                    <i class="fas fa-align-left text-primary"></i>
                                </span>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4"
                                          placeholder="Describe el tipo de contenido que incluirá esta categoría..."
                                          maxlength="120"
                                          required>{{ old('description') }}</textarea>
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

                        {{-- Preview de la categoría --}}
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-eye me-1"></i>
                                Vista Previa
                            </label>
                            <div class="card border-secondary">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-tag text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1" id="previewName">
                                                <span class="text-muted">Nombre de la categoría...</span>
                                            </h6>
                                            <p class="mb-0 text-muted small" id="previewDescription">
                                                Descripción de la categoría...
                                            </p>
                                        </div>
                                        <div>
                                            <span class="badge bg-secondary">Nueva</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Botones de acción --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                {{-- Indicador de auto-guardado --}}
                                <small id="autosaveIndicator" class="text-muted d-none">
                                    <i class="fas fa-save me-1"></i>
                                    Borrador guardado automáticamente
                                </small>
                            </div>
                            <div class="btn-group" role="group">
                                <a href="{{ route('categories.index') }}" 
                                   class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Cancelar
                                </a>
                                <button type="reset" 
                                        class="btn btn-outline-warning"
                                        onclick="clearForm()">
                                    <i class="fas fa-undo me-1"></i>
                                    Limpiar
                                </button>
                                <button type="submit" 
                                        class="btn btn-success"
                                        id="submitBtn">
                                    <i class="fas fa-save me-1"></i>
                                    Crear Categoría
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Información adicional --}}
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-lightbulb me-2"></i>
                                Consejos para crear categorías
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Usa nombres claros y descriptivos
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Evita categorías muy específicas
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Piensa en el contenido futuro
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Mantén una descripción clara
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Información importante
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-info text-primary me-2"></i>
                                    Los nombres deben ser únicos
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-info text-primary me-2"></i>
                                    Máximo 120 caracteres por campo
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-info text-primary me-2"></i>
                                    Los cambios se guardan automáticamente
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-info text-primary me-2"></i>
                                    Puedes editar después de crear
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

.card-header.bg-success {
    background-color: #198754 !important;
}

.preview-card {
    transition: all 0.3s ease;
}

.preview-card:hover {
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
    const form = document.getElementById('categoryForm');
    
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
    });
    
    descriptionInput.addEventListener('input', function() {
        updateCounter(this, descriptionCounter, 120);
        updatePreview();
    });

    
    function updatePreview() {
        const nameValue = nameInput.value.trim();
        const descriptionValue = descriptionInput.value.trim();
        
        previewName.innerHTML = nameValue || '<span class="text-muted">Nombre de la categoría...</span>';
        previewDescription.textContent = descriptionValue || 'Descripción de la categoría...';

        if (nameValue && descriptionValue) {
            previewName.parentNode.parentNode.parentNode.classList.add('border-success');
            previewName.parentNode.parentNode.parentNode.classList.remove('border-secondary');
        } else {
            previewName.parentNode.parentNode.parentNode.classList.remove('border-success');
            previewName.parentNode.parentNode.parentNode.classList.add('border-secondary');
        }
    }
    
    let nameCheckTimeout;
    
    function checkNameAvailability() {
        const name = nameInput.value.trim();
        
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
                body: JSON.stringify({ name: name })
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
            
            localStorage.setItem('category_draft', JSON.stringify(data));
            showAutosaveIndicator();
        }, 2000);
    }
    
    function showAutosaveIndicator() {
        autosaveIndicator.classList.remove('d-none');
        setTimeout(() => {
            autosaveIndicator.classList.add('d-none');
        }, 3000);
    }
    
    function loadDraft() {
        const draft = localStorage.getItem('category_draft');
        if (draft) {
            const data = JSON.parse(draft);
            if (data.name) nameInput.value = data.name;
            if (data.description) descriptionInput.value = data.description;
            updateCounter(nameInput, nameCounter, 120);
            updateCounter(descriptionInput, descriptionCounter, 120);
            updatePreview();
        }
    }
    
    nameInput.addEventListener('input', autoSave);
    descriptionInput.addEventListener('input', autoSave);
    
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Creando...';

        localStorage.removeItem('category_draft');
    });

    loadDraft();

    updateCounter(nameInput, nameCounter, 120);
    updateCounter(descriptionInput, descriptionCounter, 120);
    updatePreview();
});

function clearForm() {
    document.getElementById('categoryForm').reset();
    document.getElementById('nameCounter').textContent = '0/120';
    document.getElementById('descriptionCounter').textContent = '0/120';
    document.getElementById('nameCounter').className = 'text-muted small';
    document.getElementById('descriptionCounter').className = 'text-muted small';
    document.getElementById('nameAvailability').classList.add('d-none');

    document.getElementById('previewName').innerHTML = '<span class="text-muted">Nombre de la categoría...</span>';
    document.getElementById('previewDescription').textContent = 'Descripción de la categoría...';

    localStorage.removeItem('category_draft');

    document.getElementById('name').focus();
}
</script>
@endpush