<div class="hero-section">
    <div class="container">
        <h1 class="display-3 fw-bold mb-4">Sistema de Mayordomía Online</h1>
        <p class="lead mb-5">Gestión completa para hoteles: habitaciones, restaurante, amenidades y servicios</p>
        <?php if (!$isLoggedIn): ?>
            <a href="<?php echo BASE_URL; ?>/auth/register" class="btn btn-light btn-lg me-3">
                <i class="bi bi-person-plus"></i> Comenzar Prueba Gratuita
            </a>
            <a href="<?php echo BASE_URL; ?>/auth/login" class="btn btn-outline-light btn-lg">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
            </a>
        <?php else: ?>
            <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-light btn-lg">
                <i class="bi bi-speedometer2"></i> Ir al Dashboard
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="container my-5">
    <div class="row text-center mb-5">
        <div class="col-md-12">
            <h2 class="mb-3">Características Principales</h2>
            <p class="text-muted">Todo lo que necesitas para administrar tu hotel en un solo lugar</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-door-open feature-icon"></i>
                    <h5 class="card-title">Gestión de Habitaciones</h5>
                    <p class="card-text">Control completo de habitaciones, disponibilidad, reservas y mantenimiento.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-cup-straw feature-icon"></i>
                    <h5 class="card-title">Restaurante y Menú</h5>
                    <p class="card-text">Administra mesas, menú de platillos, pedidos y reservaciones del restaurante.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-water feature-icon"></i>
                    <h5 class="card-title">Amenidades y Servicios</h5>
                    <p class="card-text">Spa, gimnasio, piscina, transporte y más servicios para tus huéspedes.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people feature-icon"></i>
                    <h5 class="card-title">Gestión de Personal</h5>
                    <p class="card-text">Administra roles, permisos y asigna tareas a tu equipo de trabajo.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-bell feature-icon"></i>
                    <h5 class="card-title">Notificaciones en Tiempo Real</h5>
                    <p class="card-text">Alertas automáticas para reservas, servicios y cambios importantes.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up feature-icon"></i>
                    <h5 class="card-title">Reportes y Métricas</h5>
                    <p class="card-text">Dashboards con estadísticas de ocupación, ingresos y actividad.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card bg-light">
                <div class="card-body text-center py-5">
                    <h3 class="mb-3">Roles del Sistema</h3>
                    <div class="row mt-4">
                        <div class="col-md-2">
                            <i class="bi bi-star-fill text-warning" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Superadmin</h6>
                            <small class="text-muted">Gestión global</small>
                        </div>
                        <div class="col-md-2">
                            <i class="bi bi-shield-check text-primary" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Admin Hotel</h6>
                            <small class="text-muted">Control total</small>
                        </div>
                        <div class="col-md-2">
                            <i class="bi bi-clipboard-check text-success" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Gerente</h6>
                            <small class="text-muted">Restaurante</small>
                        </div>
                        <div class="col-md-2">
                            <i class="bi bi-person-badge text-info" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Hostess</h6>
                            <small class="text-muted">Bloqueos</small>
                        </div>
                        <div class="col-md-2">
                            <i class="bi bi-person-workspace text-secondary" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Colaborador</h6>
                            <small class="text-muted">Operativo</small>
                        </div>
                        <div class="col-md-2">
                            <i class="bi bi-person text-dark" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Huésped</h6>
                            <small class="text-muted">Cliente final</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12 text-center">
            <h3 class="mb-4">Planes de Suscripción</h3>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h4>Prueba Gratuita</h4>
                        </div>
                        <div class="card-body">
                            <h2 class="card-title">$0<small class="text-muted">/30 días</small></h2>
                            <ul class="list-unstyled mt-3">
                                <li><i class="bi bi-check text-success"></i> 5 Habitaciones</li>
                                <li><i class="bi bi-check text-success"></i> 5 Mesas</li>
                                <li><i class="bi bi-check text-success"></i> 5 Amenidades</li>
                                <li><i class="bi bi-check text-success"></i> 3 Colaboradores</li>
                            </ul>
                            <a href="<?php echo BASE_URL; ?>/auth/register?plan=trial" class="btn btn-outline-primary">Comenzar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <h4>Plan Mensual</h4>
                        </div>
                        <div class="card-body">
                            <h2 class="card-title">$499<small class="text-muted">/mes</small></h2>
                            <ul class="list-unstyled mt-3">
                                <li><i class="bi bi-check text-success"></i> 50 Habitaciones</li>
                                <li><i class="bi bi-check text-success"></i> 30 Mesas</li>
                                <li><i class="bi bi-check text-success"></i> 20 Amenidades</li>
                                <li><i class="bi bi-check text-success"></i> 20 Colaboradores</li>
                            </ul>
                            <a href="<?php echo BASE_URL; ?>/auth/register?plan=monthly" class="btn btn-primary">Suscribirse</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h4>Plan Anual <span class="badge bg-warning">-20%</span></h4>
                        </div>
                        <div class="card-body">
                            <h2 class="card-title">$4,790<small class="text-muted">/año</small></h2>
                            <ul class="list-unstyled mt-3">
                                <li><i class="bi bi-check text-success"></i> 100 Habitaciones</li>
                                <li><i class="bi bi-check text-success"></i> 50 Mesas</li>
                                <li><i class="bi bi-check text-success"></i> 40 Amenidades</li>
                                <li><i class="bi bi-check text-success"></i> 50 Colaboradores</li>
                                <li><i class="bi bi-star-fill text-warning"></i> Soporte prioritario</li>
                            </ul>
                            <a href="<?php echo BASE_URL; ?>/auth/register?plan=annual" class="btn btn-success">Suscribirse</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
