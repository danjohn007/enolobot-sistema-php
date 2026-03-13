<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-person-plus"></i> Nuevo Usuario</h1>
    <a href="<?php echo BASE_URL; ?>/users" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>/users/create" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                            <div class="invalid-feedback">El nombre es requerido.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Apellido *</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                            <div class="invalid-feedback">El apellido es requerido.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">Ingrese un email válido.</div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Rol *</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">Seleccione...</option>
                            <option value="restaurant_manager">Gerente de Restaurante</option>
                            <option value="hostess">Hostess</option>
                            <option value="collaborator">Colaborador</option>
                            <option value="guest">Huésped</option>
                        </select>
                        <div class="invalid-feedback">Seleccione un rol.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Contraseña *</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   minlength="6" required>
                            <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Contraseña *</label>
                            <input type="password" class="form-control" id="confirm_password" 
                                   name="confirm_password" minlength="6" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Crear Usuario
                        </button>
                        <a href="<?php echo BASE_URL; ?>/users" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-info-circle"></i> Roles Disponibles</h5>
                <ul class="small mb-0">
                    <li><strong>Gerente de Restaurante:</strong> Gestiona menú, mesas y pedidos</li>
                    <li><strong>Hostess:</strong> Controla bloqueos y disponibilidad</li>
                    <li><strong>Colaborador:</strong> Atiende solicitudes de servicio</li>
                    <li><strong>Huésped:</strong> Acceso limitado para clientes</li>
                </ul>
            </div>
        </div>

        <div class="card mt-3 bg-warning bg-opacity-10">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-shield-check"></i> Seguridad</h6>
                <p class="card-text small mb-0">
                    Las contraseñas se almacenan de forma segura con encriptación. 
                    Asegúrese de usar contraseñas seguras.
                </p>
            </div>
        </div>
    </div>
</div>

            </div>
        </main>
    </div>
</div>
