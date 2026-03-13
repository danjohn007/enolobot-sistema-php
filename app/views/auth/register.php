<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-building" style="font-size: 3rem; color: var(--secondary-color);"></i>
                        <h3 class="mt-3">Crear Cuenta</h3>
                        <p class="text-muted">Regístrate y comienza tu prueba gratuita</p>
                    </div>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> <strong>Errores:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo BASE_URL; ?>/auth/register" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">
                                    <i class="bi bi-person"></i> Nombre *
                                </label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required
                                       value="<?php echo $_POST['first_name'] ?? ''; ?>">
                                <div class="invalid-feedback">El nombre es requerido.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">
                                    <i class="bi bi-person"></i> Apellido *
                                </label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required
                                       value="<?php echo $_POST['last_name'] ?? ''; ?>">
                                <div class="invalid-feedback">El apellido es requerido.</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="hotel_name" class="form-label">
                                <i class="bi bi-building"></i> Nombre del Hotel *
                            </label>
                            <input type="text" class="form-control" id="hotel_name" name="hotel_name" required
                                   value="<?php echo $_POST['hotel_name'] ?? ''; ?>">
                            <div class="invalid-feedback">El nombre del hotel es requerido.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> Email *
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       value="<?php echo $_POST['email'] ?? ''; ?>">
                                <div class="invalid-feedback">Ingrese un email válido.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    <i class="bi bi-phone"></i> Teléfono
                                </label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                       value="<?php echo $_POST['phone'] ?? ''; ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Contraseña *
                                </label>
                                <input type="password" class="form-control" id="password" name="password" required
                                       minlength="6">
                                <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">
                                    <i class="bi bi-lock"></i> Confirmar Contraseña *
                                </label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required
                                       minlength="6">
                                <div class="invalid-feedback">Confirme su contraseña.</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="plan_id" class="form-label">
                                <i class="bi bi-star"></i> Plan de Suscripción *
                            </label>
                            <select class="form-select" id="plan_id" name="plan_id" required>
                                <?php foreach($plans as $plan): ?>
                                    <option value="<?php echo $plan['id']; ?>" 
                                            <?php echo ($selectedPlan == $plan['id']) ? 'selected' : ''; ?>>
                                        <?php echo $plan['name']; ?> - 
                                        $<?php echo number_format($plan['price'], 2); ?> 
                                        <?php if($plan['billing_period'] === 'trial'): ?>
                                            (<?php echo $plan['trial_days']; ?> días gratis)
                                        <?php else: ?>
                                            /<?php echo $plan['billing_period'] === 'monthly' ? 'mes' : 'año'; ?>
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Acepto los <a href="#" target="_blank">términos y condiciones</a>
                            </label>
                            <div class="invalid-feedback">Debe aceptar los términos.</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-person-plus"></i> Crear Cuenta
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0">¿Ya tienes cuenta?</p>
                        <a href="<?php echo BASE_URL; ?>/auth/login" class="btn btn-outline-primary mt-2">
                            <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
