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
                            <h2 class="mb-0"><?php echo $stats['total_hoteles'] ?? 0; ?></h2>
                        </div>
                        <div>
                            <i class="bi bi-building" style="font-size: 2.5rem; color: var(--secondary-color);"></i>
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
                            <h2 class="mb-0"><?php echo $stats['suscripciones_activas'] ?? 0; ?></h2>
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
                            <h2 class="mb-0"><?php echo $stats['hoteles_en_prueba'] ?? 0; ?></h2>
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
                            <h2 class="mb-0"><?php echo $stats['total_usuarios'] ?? 0; ?></h2>
                        </div>
                        <div>
                            <i class="bi bi-people" style="font-size: 2.5rem; color: var(--info-color);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Hoteles Registrados</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Total de hoteles activos en la plataforma: <strong><?php echo $stats['total_hoteles'] ?? 0; ?></strong></p>
                    <?php if (!empty($stats['hoteles_registrados'])): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Hotel</th>
                                        <th>Estado de Suscripción</th>
                                        <th>Fecha de Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats['hoteles_registrados'] as $hotel): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($hotel['name']); ?></td>
                                            <td>
                                                <?php
                                                    $statusClass = [
                                                        'active'    => 'bg-success',
                                                        'trial'     => 'bg-warning text-dark',
                                                        'expired'   => 'bg-danger',
                                                        'cancelled' => 'bg-secondary',
                                                    ][$hotel['subscription_status']] ?? 'bg-secondary';
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>">
                                                    <?php echo ucfirst($hotel['subscription_status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($hotel['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Hotel Dashboard -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
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

        <div class="col-md-4">
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

        <div class="col-md-4">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Vinos</h6>
                            <h2 class="mb-0"><?php echo $stats['total_wines'] ?? 0; ?></h2>
                            <small class="text-muted">Registrados</small>
                        </div>
                        <div>
                            <i class="bi bi-cup-hot" style="font-size: 2.5rem; color: var(--success-color);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-12">
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
