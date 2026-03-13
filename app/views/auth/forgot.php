<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-key" style="font-size: 3rem; color: var(--secondary-color);"></i>
                        <h3 class="mt-3">Recuperar Contraseña</h3>
                        <p class="text-muted">Ingresa tu email para recuperar tu cuenta</p>
                    </div>

                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email
                            </label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">Por favor ingrese su email.</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-send"></i> Enviar Instrucciones
                        </button>

                        <div class="text-center">
                            <a href="<?php echo BASE_URL; ?>/auth/login" class="text-decoration-none">
                                <i class="bi bi-arrow-left"></i> Volver al login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
