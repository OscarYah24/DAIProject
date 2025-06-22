/**
 * ==========================================================================
 * SISTEMA DE GESTIÓN DE ARTÍCULOS - FUNCIONALIDADES JAVASCRIPT
 * ==========================================================================
 */

document.addEventListener("DOMContentLoaded", function () {
    // ==========================================================================
    // INICIALIZACIÓN
    // ==========================================================================

    initializeArticlesSystem();

    function initializeArticlesSystem() {
        setupSearchFunctionality();
        setupTableSorting();
        setupFormValidation();
        setupDeleteConfirmation();
        setupTooltips();
        setupAnimations();
        setupResponsiveFeatures();
        setupAutoSave();
    }

    // ==========================================================================
    // FUNCIONALIDAD DE BÚSQUEDA
    // ==========================================================================

    function setupSearchFunctionality() {
        const searchInput = document.getElementById("search");
        if (!searchInput) return;

        let searchTimeout;

        searchInput.addEventListener("input", function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(this.value);
            }, 300);
        });

        // Limpiar búsqueda con Escape
        searchInput.addEventListener("keydown", function (e) {
            if (e.key === "Escape") {
                this.value = "";
                performSearch("");
            }
        });
    }

    function performSearch(searchTerm) {
        const tableRows = document.querySelectorAll("tbody tr");
        const noResultsRow = document.getElementById("no-results-row");
        let visibleRows = 0;

        tableRows.forEach((row) => {
            if (row.id === "no-results-row") return;

            const title =
                row
                    .querySelector("td:first-child")
                    ?.textContent.toLowerCase() || "";
            const author =
                row
                    .querySelector("td:nth-child(2)")
                    ?.textContent.toLowerCase() || "";

            if (
                title.includes(searchTerm.toLowerCase()) ||
                author.includes(searchTerm.toLowerCase()) ||
                searchTerm === ""
            ) {
                row.style.display = "";
                visibleRows++;

                // Highlight search term
                highlightSearchTerm(row, searchTerm);
            } else {
                row.style.display = "none";
            }
        });

        // Mostrar/ocultar mensaje de "sin resultados"
        if (visibleRows === 0 && searchTerm !== "") {
            showNoResults();
        } else {
            hideNoResults();
        }

        updatePaginationInfo(visibleRows);
    }

    function highlightSearchTerm(row, searchTerm) {
        if (!searchTerm) return;

        const cells = row.querySelectorAll("td:not(:last-child)");
        cells.forEach((cell) => {
            const originalText = cell.textContent;
            const regex = new RegExp(`(${searchTerm})`, "gi");
            const highlightedText = originalText.replace(
                regex,
                '<span class="search-highlight">$1</span>'
            );

            if (highlightedText !== originalText) {
                cell.innerHTML = highlightedText;
            }
        });
    }

    function showNoResults() {
        let noResultsRow = document.getElementById("no-results-row");
        if (!noResultsRow) {
            const tbody = document.querySelector("tbody");
            noResultsRow = document.createElement("tr");
            noResultsRow.id = "no-results-row";
            noResultsRow.innerHTML = `
                <td colspan="3" class="text-center py-4">
                    <i class="fas fa-search fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No se encontraron resultados para tu búsqueda</p>
                </td>
            `;
            tbody.appendChild(noResultsRow);
        }
        noResultsRow.style.display = "";
    }

    function hideNoResults() {
        const noResultsRow = document.getElementById("no-results-row");
        if (noResultsRow) {
            noResultsRow.style.display = "none";
        }
    }

    // ==========================================================================
    // ORDENACIÓN DE TABLA
    // ==========================================================================

    function setupTableSorting() {
        const sortableHeaders = document.querySelectorAll("th[data-sortable]");

        sortableHeaders.forEach((header) => {
            header.style.cursor = "pointer";
            header.addEventListener("click", function () {
                sortTable(this);
            });
        });
    }

    function sortTable(header) {
        const table = header.closest("table");
        const tbody = table.querySelector("tbody");
        const rows = Array.from(
            tbody.querySelectorAll("tr:not(#no-results-row)")
        );
        const columnIndex = Array.from(header.parentNode.children).indexOf(
            header
        );
        const isAscending = header.classList.contains("sort-asc");

        // Limpiar iconos de ordenación anteriores
        header.parentNode.querySelectorAll("th").forEach((th) => {
            th.classList.remove("sort-asc", "sort-desc");
            const icon = th.querySelector("i");
            if (icon) {
                icon.className = "fas fa-sort ms-1 text-muted";
            }
        });

        // Ordenar filas
        rows.sort((a, b) => {
            const aValue = a.children[columnIndex].textContent.trim();
            const bValue = b.children[columnIndex].textContent.trim();

            if (isAscending) {
                return bValue.localeCompare(aValue);
            } else {
                return aValue.localeCompare(bValue);
            }
        });

        // Actualizar clase e icono
        header.classList.add(isAscending ? "sort-desc" : "sort-asc");
        const icon = header.querySelector("i");
        if (icon) {
            icon.className = `fas fa-sort-${
                isAscending ? "down" : "up"
            } ms-1 text-primary`;
        }

        // Reordenar DOM
        rows.forEach((row) => tbody.appendChild(row));

        // Animar cambio
        tbody.style.opacity = "0.7";
        setTimeout(() => {
            tbody.style.opacity = "1";
        }, 150);
    }

    // ==========================================================================
    // VALIDACIÓN DE FORMULARIOS
    // ==========================================================================

    function setupFormValidation() {
        const forms = document.querySelectorAll("form");

        forms.forEach((form) => {
            form.addEventListener("submit", function (e) {
                if (!validateForm(this)) {
                    e.preventDefault();
                }
            });

            // Validación en tiempo real
            const inputs = form.querySelectorAll("input, textarea, select");
            inputs.forEach((input) => {
                input.addEventListener("blur", function () {
                    validateField(this);
                });

                input.addEventListener("input", function () {
                    clearFieldError(this);
                });
            });
        });
    }

    function validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll("[required]");

        requiredFields.forEach((field) => {
            if (!validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = "";

        // Validar campo requerido
        if (field.hasAttribute("required") && !value) {
            errorMessage = "Este campo es obligatorio.";
            isValid = false;
        }

        // Validaciones específicas por tipo
        if (value && field.type === "email") {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                errorMessage = "Ingresa un email válido.";
                isValid = false;
            }
        }

        if (value && field.name === "title" && value.length < 5) {
            errorMessage = "El título debe tener al menos 5 caracteres.";
            isValid = false;
        }

        if (value && field.name === "content" && value.length < 20) {
            errorMessage = "El contenido debe tener al menos 20 caracteres.";
            isValid = false;
        }

        if (!isValid) {
            showFieldError(field, errorMessage);
        } else {
            clearFieldError(field);
        }

        return isValid;
    }

    function showFieldError(field, message) {
        clearFieldError(field);

        field.classList.add("is-invalid");

        const errorDiv = document.createElement("div");
        errorDiv.className = "invalid-feedback";
        errorDiv.textContent = message;

        field.parentNode.appendChild(errorDiv);
    }

    function clearFieldError(field) {
        field.classList.remove("is-invalid");
        const existingError =
            field.parentNode.querySelector(".invalid-feedback");
        if (existingError) {
            existingError.remove();
        }
    }

    // ==========================================================================
    // CONFIRMACIÓN DE ELIMINACIÓN
    // ==========================================================================

    function setupDeleteConfirmation() {
        const deleteButtons = document.querySelectorAll(
            '[data-bs-target="#deleteModal"]'
        );

        deleteButtons.forEach((button) => {
            button.addEventListener("click", function () {
                const articleTitle =
                    this.getAttribute("data-article-title") ||
                    this.closest("tr")?.querySelector("td:first-child")
                        ?.textContent ||
                    "este artículo";

                const modal = document.querySelector("#deleteModal");
                if (modal) {
                    const modalBody = modal.querySelector(".modal-body p");
                    if (modalBody) {
                        modalBody.innerHTML = `¿Estás seguro de que deseas eliminar el artículo <strong>"${articleTitle}"</strong>?`;
                    }
                }
            });
        });

        // Prevenir eliminación accidental con doble clic
        const deleteSubmitButtons = document.querySelectorAll(
            '#deleteModal .btn-danger[type="submit"]'
        );
        deleteSubmitButtons.forEach((button) => {
            button.addEventListener("click", function () {
                this.disabled = true;
                this.innerHTML =
                    '<span class="loading me-2"></span>Eliminando...';
            });
        });
    }

    // ==========================================================================
    // TOOLTIPS Y AYUDAS
    // ==========================================================================

    function setupTooltips() {
        // Inicializar tooltips de Bootstrap si está disponible
        if (typeof bootstrap !== "undefined") {
            const tooltipTriggerList = [].slice.call(
                document.querySelectorAll('[data-bs-toggle="tooltip"]')
            );
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Tooltips personalizados para iconos
        const icons = document.querySelectorAll(".fas, .far, .fab");
        icons.forEach((icon) => {
            if (
                !icon.hasAttribute("title") &&
                icon.parentNode.tagName !== "BUTTON"
            ) {
                const context = getIconContext(icon);
                if (context) {
                    icon.setAttribute("title", context);
                }
            }
        });
    }

    function getIconContext(icon) {
        const iconClass = icon.className;

        if (iconClass.includes("fa-edit")) return "Editar";
        if (iconClass.includes("fa-trash")) return "Eliminar";
        if (iconClass.includes("fa-eye")) return "Ver detalles";
        if (iconClass.includes("fa-plus")) return "Crear nuevo";
        if (iconClass.includes("fa-save")) return "Guardar";
        if (iconClass.includes("fa-times")) return "Cancelar";
        if (iconClass.includes("fa-arrow-left")) return "Volver";
        if (iconClass.includes("fa-search")) return "Buscar";

        return null;
    }

    // ==========================================================================
    // ANIMACIONES Y TRANSICIONES
    // ==========================================================================

    function setupAnimations() {
        // Animación de entrada para elementos
        const animatedElements = document.querySelectorAll(
            ".card, .table, .alert"
        );

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = "0";
                    entry.target.style.transform = "translateY(20px)";

                    setTimeout(() => {
                        entry.target.style.transition =
                            "opacity 0.6s ease, transform 0.6s ease";
                        entry.target.style.opacity = "1";
                        entry.target.style.transform = "translateY(0)";
                    }, 100);

                    observer.unobserve(entry.target);
                }
            });
        });

        animatedElements.forEach((el) => observer.observe(el));

        // Animación para botones
        const buttons = document.querySelectorAll(".btn");
        buttons.forEach((button) => {
            button.addEventListener("click", function () {
                this.style.transform = "scale(0.95)";
                setTimeout(() => {
                    this.style.transform = "";
                }, 150);
            });
        });
    }

    // ==========================================================================
    // CARACTERÍSTICAS RESPONSIVE
    // ==========================================================================

    function setupResponsiveFeatures() {
        // Colapsar sidebar en móviles
        const sidebarToggle = document.getElementById("sidebar-toggle");
        const sidebar = document.querySelector(".sidebar");

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener("click", function () {
                sidebar.classList.toggle("show");
            });
        }

        // Ajustar tabla en móviles
        adjustTableForMobile();
        window.addEventListener("resize", adjustTableForMobile);
    }

    function adjustTableForMobile() {
        const table = document.querySelector(".table");
        if (!table) return;

        if (window.innerWidth < 768) {
            // Convertir tabla a cards en móvil
            if (!table.classList.contains("mobile-cards")) {
                convertTableToCards(table);
            }
        } else {
            // Volver a tabla normal
            if (table.classList.contains("mobile-cards")) {
                convertCardsToTable(table);
            }
        }
    }

    function convertTableToCards(table) {
        // Implementación para convertir tabla a tarjetas en móvil
        table.classList.add("mobile-cards");
        // ... lógica adicional
    }

    function convertCardsToTable(table) {
        // Implementación para volver a tabla
        table.classList.remove("mobile-cards");
        // ... lógica adicional
    }

    // ==========================================================================
    // AUTO-GUARDADO
    // ==========================================================================

    function setupAutoSave() {
        const forms = document.querySelectorAll("form[data-autosave]");

        forms.forEach((form) => {
            const inputs = form.querySelectorAll("input, textarea, select");

            inputs.forEach((input) => {
                input.addEventListener(
                    "input",
                    debounce(() => {
                        saveFormData(form);
                    }, 2000)
                );
            });

            // Restaurar datos al cargar
            restoreFormData(form);
        });
    }

    function saveFormData(form) {
        const formData = new FormData(form);
        const data = {};

        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        const formId =
            form.id || "form-" + Math.random().toString(36).substr(2, 9);
        localStorage.setItem("autosave-" + formId, JSON.stringify(data));

        showAutoSaveIndicator();
    }

    function restoreFormData(form) {
        const formId = form.id || "";
        const savedData = localStorage.getItem("autosave-" + formId);

        if (savedData) {
            const data = JSON.parse(savedData);

            Object.keys(data).forEach((key) => {
                const input = form.querySelector(`[name="${key}"]`);
                if (input && !input.value) {
                    input.value = data[key];
                }
            });
        }
    }

    function showAutoSaveIndicator() {
        const indicator = document.createElement("div");
        indicator.className = "alert alert-success position-fixed";
        indicator.style.cssText =
            "top: 20px; right: 20px; z-index: 9999; opacity: 0;";
        indicator.innerHTML =
            '<i class="fas fa-check me-2"></i>Cambios guardados automáticamente';

        document.body.appendChild(indicator);

        setTimeout(() => {
            indicator.style.transition = "opacity 0.3s";
            indicator.style.opacity = "1";
        }, 100);

        setTimeout(() => {
            indicator.style.opacity = "0";
            setTimeout(() => indicator.remove(), 300);
        }, 2000);
    }

    // ==========================================================================
    // UTILIDADES
    // ==========================================================================

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function updatePaginationInfo(visibleRows) {
        const info = document.querySelector(".dataTables_info");
        if (info) {
            info.textContent = `Showing 1 to ${visibleRows} of ${visibleRows} entries`;
        }
    }

    // ==========================================================================
    // MANEJO DE ERRORES GLOBALES
    // ==========================================================================

    window.addEventListener("error", function (e) {
        console.error("Error en articles.js:", e.error);
    });

    // ==========================================================================
    // EXPORTAR FUNCIONES GLOBALES
    // ==========================================================================

    window.ArticlesSystem = {
        performSearch,
        validateForm,
        showAutoSaveIndicator,
    };
});
