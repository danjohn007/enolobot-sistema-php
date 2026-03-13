<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-building" style="font-size: 3rem; color: var(--secondary-color);"></i>
                        <h3 class="mt-3">Iniciar Sesión</h3>
                        <p class="text-muted">Accede a tu panel de administración</p>
                    </div>

                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success">
                            <?php 
                                echo $_SESSION['success_message']; 
                                unset($_SESSION['success_message']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo BASE_URL; ?>/auth/login" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email
                            </label>
                            <input type="email" class="form-control" id="email" name="email" required 
                                   placeholder="tu@email.com" value="<?php echo $_POST['email'] ?? ''; ?>">
                            <div class="invalid-feedback">
                                Por favor ingrese un email válido.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i> Contraseña
                            </label>
                            <input type="password" class="form-control" id="password" name="password" required 
                                   placeholder="••••••••">
                            <div class="invalid-feedback">
                                Por favor ingrese su contraseña.
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">
                                Recordarme
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                        </button>

                        <div class="text-center">
                            <a href="<?php echo BASE_URL; ?>/auth/forgot" class="text-decoration-none">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0">¿No tienes cuenta?</p>
                        <a href="<?php echo BASE_URL; ?>/auth/register" class="btn btn-outline-primary mt-2">
                            <i class="bi bi-person-plus"></i> Crear cuenta nueva
                        </a>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="mb-2"><i class="bi bi-info-circle"></i> Usuarios de Ejemplo:</h6>
                        <small class="text-muted">
                            <strong>Superadmin:</strong> superadmin@majorbot.com<br>
                            <strong>Admin Hotel:</strong> admin@granplaza.com<br>
                            <strong>Gerente Rest.:</strong> restaurant@granplaza.com<br>
                            <strong>Hostess:</strong> hostess@granplaza.com<br>
                            <strong>Colaborador:</strong> staff@granplaza.com<br>
                            <strong>Huésped:</strong> guest@example.com<br>
                            <strong>Contraseña:</strong> password
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
