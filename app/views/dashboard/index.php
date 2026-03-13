<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-calendar"></i> Hoy
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-calendar-week"></i> Semana
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-calendar-month"></i> Mes
            </button>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> 
            Bienvenido, <strong><?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?></strong> 
            - Rol: <span class="badge bg-primary"><?php echo ucfirst(str_replace('_', ' ', $role)); ?></span>
        </div>
    </div>
</div>

<?php if ($role === 'superadmin'): ?>
    <!-- Superadmin Dashboard -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Hoteles</h6>
                            <h2 class="mb-0"><?php echo $stats['total_hotels'] ?? 0; ?></h2>
                        </div>
                        <div>
                            <i class="bi bi-buildings" style="font-size: 2.5rem; color: var(--secondary-color);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Suscripciones Activas</h6>
                            <h2 class="mb-0"><?php echo $stats['active_subscriptions'] ?? 0; ?></h2>
                        </div>
                        <div>
                            <i class="bi bi-check-circle" style="font-size: 2.5rem; color: var(--success-color);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Hoteles en Prueba</h6>
                            <h2 class="mb-0"><?php echo $stats['trial_hotels'] ?? 0; ?></h2>
                        </div>
                        <div>
                            <i class="bi bi-clock" style="font-size: 2.5rem; color: var(--warning-color);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Usuarios</h6>
                            <h2 class="mb-0"><?php echo $stats['total_users'] ?? 0; ?></h2>
                        </div>
                        <div>
                            <i class="bi bi-people" style="font-size: 2.5rem; color: var(--info-color);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Hoteles Registrados</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Gestiona todos los hoteles registrados en la plataforma</p>
                    <a href="<?php echo BASE_URL; ?>/hotels" class="btn btn-primary">
                        <i class="bi bi-buildings"></i> Ver Hoteles
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Hotel Dashboard -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Habitaciones</h6>
                            <h2 class="mb-0"><?php echo $stats['total_rooms'] ?? 0; ?></h2>
                            <small class="text-success">
                                <i class="bi bi-check-circle"></i> <?php echo $stats['available_rooms'] ?? 0; ?> disponibles
                            </small>
                        </div>
                        <div>
                            <i class="bi bi-door-open" style="font-size: 2.5rem; color: var(--secondary-color);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Mesas</h6>
                            <h2 class="mb-0"><?php echo $stats['total_tables'] ?? 0; ?></h2>
                            <small class="text-success">
                                <i class="bi bi-check-circle"></i> <?php echo $stats['available_tables'] ?? 0; ?> disponibles
                            </small>
                        </div>
                        <div>
                            <i class="bi bi-table" style="font-size: 2.5rem; color: var(--success-color);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Amenidades</h6>
                            <h2 class="mb-0"><?php echo $stats['total_amenities'] ?? 0; ?></h2>
                            <small class="text-muted">Activas</small>
                        </div>
                        <div>
                            <i class="bi bi-water" style="font-size: 2.5rem; color: var(--warning-color);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Solicitudes</h6>
                            <h2 class="mb-0"><?php echo $stats['pending_services'] ?? 0; ?></h2>
                            <small class="text-danger">Pendientes</small>
                        </div>
                        <div>
                            <i class="bi bi-bell" style="font-size: 2.5rem; color: var(--danger-color);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Reservaciones Recientes</h5>
                    <a href="<?php echo BASE_URL; ?>/reservations/rooms" class="btn btn-sm btn-primary">Ver todas</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($stats['recent_reservations'])): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach($stats['recent_reservations'] as $reservation): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?php echo $reservation['first_name'] . ' ' . $reservation['last_name']; ?></h6>
                                        <small class="badge badge-status 
                                            <?php 
                                                echo $reservation['status'] === 'confirmed' ? 'bg-success' : 
                                                     ($reservation['status'] === 'pending' ? 'bg-warning' : 'bg-secondary');
                                            ?>">
                                            <?php echo ucfirst($reservation['status']); ?>
                                        </small>
                                    </div>
                                    <p class="mb-1">
                                        <i class="bi bi-door-open"></i> Habitación: <?php echo $reservation['room_number']; ?>
                                    </p>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> 
                                        <?php echo date('d/m/Y', strtotime($reservation['check_in'])); ?> - 
                                        <?php echo date('d/m/Y', strtotime($reservation['check_out'])); ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">No hay reservaciones recientes</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Solicitudes Pendientes</h5>
                    <a href="<?php echo BASE_URL; ?>/services" class="btn btn-sm btn-primary">Ver todas</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($stats['pending_requests'])): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach($stats['pending_requests'] as $request): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?php echo $request['service_type']; ?></h6>
                                        <small class="badge badge-status 
                                            <?php 
                                                echo $request['priority'] === 'urgent' ? 'bg-danger' : 
                                                     ($request['priority'] === 'high' ? 'bg-warning' : 'bg-info');
                                            ?>">
                                            <?php echo ucfirst($request['priority']); ?>
                                        </small>
                                    </div>
                                    <p class="mb-1"><?php echo substr($request['description'], 0, 50) . '...'; ?></p>
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i> <?php echo $request['first_name'] . ' ' . $request['last_name']; ?>
                                        <?php if ($request['room_number']): ?>
                                            - Hab. <?php echo $request['room_number']; ?>
                                        <?php endif; ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">No hay solicitudes pendientes</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

            </div>
        </main>
    </div>
</div>
