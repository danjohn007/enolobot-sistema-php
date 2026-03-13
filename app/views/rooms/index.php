<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-door-open"></i> Gestión de Habitaciones</h1>
    <?php if ($role === 'hotel_admin'): ?>
        <a href="<?php echo BASE_URL; ?>/rooms/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Habitación
        </a>
    <?php endif; ?>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-x-circle"></i> <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tipo de Habitación</label>
                        <select class="form-select" name="room_type">
                            <option value="">Todos</option>
                            <option value="single">Individual</option>
                            <option value="double">Doble</option>
                            <option value="suite">Suite</option>
                            <option value="deluxe">Deluxe</option>
                            <option value="presidential">Presidential</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="status">
                            <option value="">Todos</option>
                            <option value="available">Disponible</option>
                            <option value="occupied">Ocupada</option>
                            <option value="maintenance">Mantenimiento</option>
                            <option value="blocked">Bloqueada</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Capacidad mínima</label>
                        <input type="number" class="form-control" name="min_capacity" min="1">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Precio máximo</label>
                        <input type="number" class="form-control" name="max_price" step="0.01">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-secondary w-100">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($rooms)): ?>
        <div class="col-md-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> No hay habitaciones registradas
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($rooms as $room): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-door-open"></i> Habitación <?php echo $room['room_number']; ?>
                        </h5>
                        <span class="badge badge-status 
                            <?php 
                                echo $room['status'] === 'available' ? 'bg-success' : 
                                     ($room['status'] === 'occupied' ? 'bg-danger' : 
                                      ($room['status'] === 'maintenance' ? 'bg-warning' : 'bg-secondary'));
                            ?>">
                            <?php 
                                $statusText = [
                                    'available' => 'Disponible',
                                    'occupied' => 'Ocupada',
                                    'maintenance' => 'Mantenimiento',
                                    'blocked' => 'Bloqueada'
                                ];
                                echo $statusText[$room['status']] ?? $room['status'];
                            ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <strong>Tipo:</strong> 
                            <?php 
                                $typeText = [
                                    'single' => 'Individual',
                                    'double' => 'Doble',
                                    'suite' => 'Suite',
                                    'deluxe' => 'Deluxe',
                                    'presidential' => 'Presidential'
                                ];
                                echo $typeText[$room['room_type']] ?? $room['room_type'];
                            ?>
                        </p>
                        <p class="card-text">
                            <strong><i class="bi bi-people"></i> Capacidad:</strong> <?php echo $room['capacity']; ?> personas
                        </p>
                        <?php if ($room['floor']): ?>
                            <p class="card-text">
                                <strong><i class="bi bi-building"></i> Piso:</strong> <?php echo $room['floor']; ?>
                            </p>
                        <?php endif; ?>
                        <p class="card-text">
                            <strong><i class="bi bi-currency-dollar"></i> Precio:</strong> 
                            $<?php echo number_format($room['price_per_night'], 2); ?> / noche
                        </p>
                        <?php if ($room['description']): ?>
                            <p class="card-text text-muted small">
                                <?php echo substr($room['description'], 0, 100); ?>
                                <?php echo strlen($room['description']) > 100 ? '...' : ''; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="btn-group w-100" role="group">
                            <?php if ($role === 'hotel_admin'): ?>
                                <a href="<?php echo BASE_URL; ?>/rooms/edit/<?php echo $room['id']; ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="if(confirmDelete('¿Eliminar habitación <?php echo $room['room_number']; ?>?')) 
                                                 window.location='<?php echo BASE_URL; ?>/rooms/delete/<?php echo $room['id']; ?>'">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                    data-bs-toggle="modal" data-bs-target="#statusModal<?php echo $room['id']; ?>">
                                <i class="bi bi-gear"></i> Estado
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Change Modal -->
            <div class="modal fade" id="statusModal<?php echo $room['id']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cambiar Estado - Habitación <?php echo $room['room_number']; ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="<?php echo BASE_URL; ?>/rooms/changeStatus/<?php echo $room['id']; ?>">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nuevo Estado</label>
                                    <select class="form-select" name="status" required>
                                        <option value="available" <?php echo $room['status'] === 'available' ? 'selected' : ''; ?>>Disponible</option>
                                        <option value="occupied" <?php echo $room['status'] === 'occupied' ? 'selected' : ''; ?>>Ocupada</option>
                                        <option value="maintenance" <?php echo $room['status'] === 'maintenance' ? 'selected' : ''; ?>>Mantenimiento</option>
                                        <option value="blocked" <?php echo $room['status'] === 'blocked' ? 'selected' : ''; ?>>Bloqueada</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

            </div>
        </main>
    </div>
</div>
