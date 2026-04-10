@extends('administrator.app')

@section('title', 'Inicio')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1><i class="fas fa-users me-2"></i>Gestión de Usuarios</h1>
            <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#userModal" id="createUserBtn">
                <i class="fas fa-user-plus me-2"></i>Crear Usuario
            </button>
        </div>
    </div>

    <!-- Filtros de búsqueda -->
    <div class="filters-section mb-4">
        <h5 class="mb-3"><i class="fas fa-filter me-2"></i>Filtros de Búsqueda</h5>
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control" id="filterName" placeholder="Buscar por nombre">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="filterLastName" placeholder="Buscar por apellido">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="filterCountry" placeholder="Buscar por país">
            </div>
            <div class="col-md-3">
                <input type="email" class="form-control" id="filterEmail" placeholder="Buscar por correo">
            </div>
        </div>
    </div>

    <!-- Tabla de usuarios -->
    <div class="table-container">
        <div class="loading text-center py-4" id="loadingIndicator">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2 text-muted">Cargando usuarios...</p>
        </div>
        
        <div class="table-responsive" id="usersTableContainer">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Correo electrónico</th>
                        <th>País</th>
                        <th>Número telefónico</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <!-- Los usuarios se cargarán aquí dinámicamente -->
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <nav aria-label="Navegación de páginas">
            <ul class="pagination justify-content-center" id="pagination">
                <!-- La paginación se generará dinámicamente -->
            </ul>
        </nav>
    </div>
</div>
<!-- Modal para crear/editar usuario -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Crear Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="userId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Correo electrónico *</label>
                            <input type="email" class="form-control" id="email" required>
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" id="name" required>
                            <div class="invalid-feedback" id="nameError"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstLastName" class="form-label">Primer apellido *</label>
                            <input type="text" class="form-control" id="firstLastName" required>
                            <div class="invalid-feedback" id="firstLastNameError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="secondLastName" class="form-label">Segundo apellido</label>
                            <input type="text" class="form-control" id="secondLastName">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">País *</label>
                            <input type="text" class="form-control" id="country" required>
                            <div class="invalid-feedback" id="countryError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Número telefónico *</label>
                            <input type="text" class="form-control" id="phone" required>
                            <div class="invalid-feedback" id="phoneError"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Rol *</label>
                            <select class="form-control" id="role" required>
                                <option value="">Seleccionar rol</option>
                                <option value="admin">Administrador</option>
                                <option value="editor">Editor</option>
                                <option value="user">Usuario</option>
                            </select>
                            <div class="invalid-feedback" id="roleError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="active" class="form-label">Estado</label>
                            <select class="form-control" id="active">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="sendPassword">
                        <label class="form-check-label" for="sendPassword">Enviar nueva contraseña al correo</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary-custom" id="saveUserBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar al usuario <span id="userToDeleteName" class="fw-bold"></span>? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger-custom" id="confirmDeleteBtn">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #5bc0de;
        --secondary-color: #007bff;
        --accent-color: #17a2b8;
        --text-color: #333;
        --light-bg: #f0f8ff;
        --white: #ffffff;
        --dark-blue: #0056b3;
        --error-color: #e74c3c;
        --success-color: #2ecc71;
        --warning-color: #f39c12;
    }
    
    .header {
        background: linear-gradient(to right, #5bc0de, #17a2b8);
        color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .header h1 {
        margin: 0;
        font-weight: 600;
    }
    
    .btn-primary-custom {
        background-color: var(--primary-color);
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        color: white;
    }
    
    .btn-primary-custom:hover {
        background-color: var(--accent-color);
        box-shadow: 0 4px 12px rgba(91, 192, 222, 0.4);
        transform: translateY(-2px);
    }
    
    .btn-secondary-custom {
        background-color: transparent;
        border: 1px solid var(--primary-color);
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        color: var(--primary-color);
    }
    
    .btn-secondary-custom:hover {
        background-color: var(--light-bg);
    }
    
    .btn-danger-custom {
        background-color: var(--error-color);
        border: none;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
        color: white;
    }
    
    .btn-danger-custom:hover {
        background-color: #c0392b;
    }
    
    .btn-edit-custom {
        background-color: var(--warning-color);
        border: none;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
        color: white;
        margin-right: 5px;
    }
    
    .btn-edit-custom:hover {
        background-color: #e67e22;
    }
    
    .filters-section {
        background-color: var(--white);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    .table-container {
        background-color: var(--white);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    .table th {
        background-color: var(--light-bg);
        color: var(--text-color);
        font-weight: 600;
    }
    
    .status-active {
        color: var(--success-color);
        font-weight: 600;
    }
    
    .status-inactive {
        color: var(--error-color);
        font-weight: 600;
    }
    
    .modal-header {
        background: linear-gradient(to right, #5bc0de, #17a2b8);
        color: white;
    }
    
    .modal-title {
        font-weight: 600;
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(91, 192, 222, 0.25);
        border-color: var(--primary-color);
    }
    
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .loading {
        display: none;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .btn-edit-custom, .btn-danger-custom {
            padding: 3px 6px;
            font-size: 0.75rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables globales
        let currentPage = 1;
        let totalPages = 1;
        let users = [];
        let userToDelete = null;
        
        // Elementos del DOM
        const usersTableBody = document.getElementById('usersTableBody');
        const pagination = document.getElementById('pagination');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const userModal = new bootstrap.Modal(document.getElementById('userModal'));
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const userForm = document.getElementById('userForm');
        const userModalLabel = document.getElementById('userModalLabel');
        const saveUserBtn = document.getElementById('saveUserBtn');
        const createUserBtn = document.getElementById('createUserBtn');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const userToDeleteName = document.getElementById('userToDeleteName');
        
        // Filtros
        const filterName = document.getElementById('filterName');
        const filterLastName = document.getElementById('filterLastName');
        const filterCountry = document.getElementById('filterCountry');
        const filterEmail = document.getElementById('filterEmail');
        
        // CSRF Token para Laravel
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Cargar usuarios al iniciar
        loadUsers();
        
        // Event Listeners
        createUserBtn.addEventListener('click', function() {
            resetForm();
            userModalLabel.textContent = 'Crear Usuario';
            saveUserBtn.textContent = 'Guardar';
        });
        
        saveUserBtn.addEventListener('click', saveUser);
        
        confirmDeleteBtn.addEventListener('click', deleteUser);
        
        // Filtros en tiempo real
        [filterName, filterLastName, filterCountry, filterEmail].forEach(filter => {
            filter.addEventListener('input', function() {
                // Debounce para evitar muchas llamadas
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    currentPage = 1;
                    loadUsers();
                }, 500);
            });
        });
        
        // Funciones
        
        // Cargar usuarios desde el servidor
        function loadUsers() {
            showLoading(true);
            
            // Parámetros de filtro
            const params = new URLSearchParams({
                page: currentPage,
                name: filterName.value,
                last_name: filterLastName.value,
                country: filterCountry.value,
                email: filterEmail.value
            });
            
            // Llamada AJAX a Laravel
            fetch(`/admin/usuarios?${params}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                users = data.users;
                currentPage = data.current_page;
                totalPages = data.last_page;
                
                // Renderizar tabla
                renderUsersTable(data.users);
                
                // Renderizar paginación
                renderPagination();
                
                showLoading(false);
            })
            .catch(error => {
                console.error('Error al cargar usuarios:', error);
                showLoading(false);
                alert('Error al cargar los usuarios');
            });
        }
        
        // Renderizar tabla de usuarios
        function renderUsersTable(users) {
            usersTableBody.innerHTML = '';
            
            if (users.length === 0) {
                usersTableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-users fa-2x mb-3 text-muted"></i>
                            <p class="text-muted">No se encontraron usuarios</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            users.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.first_last_name} ${user.second_last_name || ''}</td>
                    <td>${user.email}</td>
                    <td>${user.country}</td>
                    <td>${user.phone}</td>
                    <td>
                        <span class="${user.active ? 'status-active' : 'status-inactive'}">
                            ${user.active ? 'Sí' : 'No'}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-edit-custom edit-user" data-id="${user.id}">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn btn-danger-custom delete-user" data-id="${user.id}">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </td>
                `;
                usersTableBody.appendChild(row);
            });
            
            // Agregar event listeners a los botones de editar y eliminar
            document.querySelectorAll('.edit-user').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-id');
                    editUser(userId);
                });
            });
            
            document.querySelectorAll('.delete-user').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-id');
                    confirmDelete(userId);
                });
            });
        }
        
        // Renderizar paginación
        function renderPagination() {
            pagination.innerHTML = '';
            
            if (totalPages <= 1) return;
            
            // Botón anterior
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `
                <a class="page-link" href="#" aria-label="Anterior" ${currentPage === 1 ? 'tabindex="-1" aria-disabled="true"' : ''}>
                    <span aria-hidden="true">&laquo;</span>
                </a>
            `;
            if (currentPage > 1) {
                prevLi.querySelector('a').addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage--;
                    loadUsers();
                });
            }
            pagination.appendChild(prevLi);
            
            // Números de página
            for (let i = 1; i <= totalPages; i++) {
                const pageLi = document.createElement('li');
                pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
                pageLi.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                
                pageLi.querySelector('a').addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage = i;
                    loadUsers();
                });
                
                pagination.appendChild(pageLi);
            }
            
            // Botón siguiente
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
            nextLi.innerHTML = `
                <a class="page-link" href="#" aria-label="Siguiente" ${currentPage === totalPages ? 'tabindex="-1" aria-disabled="true"' : ''}>
                    <span aria-hidden="true">&raquo;</span>
                </a>
            `;
            if (currentPage < totalPages) {
                nextLi.querySelector('a').addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage++;
                    loadUsers();
                });
            }
            pagination.appendChild(nextLi);
        }
        
        // Editar usuario
        function editUser(userId) {
            const user = users.find(u => u.id == userId);
            if (!user) return;
            
            // Llenar el formulario con los datos del usuario
            document.getElementById('userId').value = user.id;
            document.getElementById('email').value = user.email;
            document.getElementById('name').value = user.name;
            document.getElementById('firstLastName').value = user.first_last_name;
            document.getElementById('secondLastName').value = user.second_last_name || '';
            document.getElementById('country').value = user.country;
            document.getElementById('phone').value = user.phone;
            
            // Cambiar título del modal
            userModalLabel.textContent = 'Editar Usuario';
            saveUserBtn.textContent = 'Actualizar';
            
            // Mostrar modal
            userModal.show();
        }
        
        // Guardar usuario (crear o actualizar)
        function saveUser() {
            // Obtener datos del formulario
            const userId = document.getElementById('userId').value;
            const email = document.getElementById('email').value;
            const name = document.getElementById('name').value;
            const firstLastName = document.getElementById('firstLastName').value;
            const secondLastName = document.getElementById('secondLastName').value;
            const country = document.getElementById('country').value;
            const phone = document.getElementById('phone').value;
            const sendPassword = document.getElementById('sendPassword').checked;
            
            // Validación básica
            if (!email || !name || !firstLastName || !country || !phone) {
                alert('Por favor, complete todos los campos obligatorios.');
                return;
            }
            
            // Datos a enviar
            const userData = {
                email: email,
                name: name,
                first_last_name: firstLastName,
                second_last_name: secondLastName,
                country: country,
                phone: phone,
                send_password: sendPassword
            };
            
            // Configurar la petición
            const url = userId ? `/admin/usuarios/${userId}` : '/admin/usuarios';
            const method = userId ? 'PUT' : 'POST';
            
            showLoading(true);
            
            // Llamada AJAX a Laravel
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(userData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recargar la tabla
                    loadUsers();
                    
                    // Cerrar modal
                    userModal.hide();
                    
                    // Mostrar mensaje de éxito
                    alert(`Usuario ${userId ? 'actualizado' : 'creado'} correctamente.`);
                } else {
                    alert('Error: ' + data.message);
                }
                
                showLoading(false);
            })
            .catch(error => {
                console.error('Error al guardar usuario:', error);
                alert('Error al guardar el usuario');
                showLoading(false);
            });
        }
        
        // Confirmar eliminación
        function confirmDelete(userId) {
            const user = users.find(u => u.id == userId);
            if (!user) return;
            
            userToDelete = user;
            userToDeleteName.textContent = `${user.name} ${user.first_last_name}`;
            deleteModal.show();
        }
        
        // Eliminar usuario
        function deleteUser() {
            if (!userToDelete) return;
            
            showLoading(true);
            
            // Llamada AJAX a Laravel
            fetch(`/admin/usuarios/${userToDelete.id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recargar la tabla
                    loadUsers();
                    
                    // Cerrar modal
                    deleteModal.hide();
                    
                    // Mostrar mensaje de éxito
                    alert('Usuario eliminado correctamente.');
                } else {
                    alert('Error: ' + data.message);
                }
                
                showLoading(false);
            })
            .catch(error => {
                console.error('Error al eliminar usuario:', error);
                alert('Error al eliminar el usuario');
                showLoading(false);
            });
        }
        
        // Resetear formulario
        function resetForm() {
            userForm.reset();
            document.getElementById('userId').value = '';
            document.getElementById('sendPassword').checked = false;
        }
        
        // Mostrar/ocultar indicador de carga
        function showLoading(show) {
            if (show) {
                loadingIndicator.style.display = 'block';
                usersTableContainer.style.display = 'none';
            } else {
                loadingIndicator.style.display = 'none';
                usersTableContainer.style.display = 'block';
            }
        }
    });
</script>
@endsection