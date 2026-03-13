<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-water"></i> Gestión de Amenidades</h1>
    <?php if ($role === 'hotel_admin'): ?>
        <a href="<?php echo BASE_URL; ?>/amenities/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Amenidad
        </a>
    <?php endif; ?>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <?php if (empty($amenities)): ?>
        <div class="col-md-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> No hay amenidades registradas
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($amenities as $amenity): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?php echo $amenity['name']; ?></h5>
                        <span class="badge badge-status 
                            <?php 
                                echo $amenity['status'] === 'available' ? 'bg-success' : 
                                     ($amenity['status'] === 'occupied' ? 'bg-danger' : 
                                      ($amenity['status'] === 'maintenance' ? 'bg-warning' : 'bg-secondary'));
                            ?>">
                            <?php 
                                $statusText = [
                                    'available' => 'Disponible',
                                    'occupied' => 'En uso',
                                    'maintenance' => 'Mantenimiento',
                                    'blocked' => 'Bloqueada'
                                ];
                                echo $statusText[$amenity['status']] ?? $amenity['status'];
                            ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <?php if ($amenity['category']): ?>
                            <p class="card-text">
                                <span class="badge bg-info"><?php echo $amenity['category']; ?></span>
                            </p>
                        <?php endif; ?>
                        <?php if ($amenity['description']): ?>
                            <p class="card-text text-muted small">
                                <?php echo substr($amenity['description'], 0, 100); ?>
                                <?php echo strlen($amenity['description']) > 100 ? '...' : ''; ?>
                            </p>
                        <?php endif; ?>
                        <p class="card-text">
                            <strong><i class="bi bi-people"></i> Capacidad:</strong> <?php echo $amenity['capacity']; ?> personas
                        </p>
                        <?php if ($amenity['price'] > 0): ?>
                            <p class="card-text">
                                <strong><i class="bi bi-currency-dollar"></i> Precio:</strong> 
                                $<?php echo number_format($amenity['price'], 2); ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($amenity['operating_hours_start'] && $amenity['operating_hours_end']): ?>
                            <p class="card-text small">
                                <i class="bi bi-clock"></i> 
                                <?php echo date('H:i', strtotime($amenity['operating_hours_start'])); ?> - 
                                <?php echo date('H:i', strtotime($amenity['operating_hours_end'])); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="btn-group w-100" role="group">
                            <?php if ($role === 'hotel_admin'): ?>
                                <a href="<?php echo BASE_URL; ?>/amenities/edit/<?php echo $amenity['id']; ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                            <?php endif; ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                    data-bs-toggle="modal" data-bs-target="#statusModal<?php echo $amenity['id']; ?>">
                                <i class="bi bi-gear"></i> Estado
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Change Modal -->
            <div class="modal fade" id="statusModal<?php echo $amenity['id']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cambiar Estado - <?php echo $amenity['name']; ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="<?php echo BASE_URL; ?>/amenities/changeStatus/<?php echo $amenity['id']; ?>">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nuevo Estado</label>
                                    <select class="form-select" name="status" required>
                                        <option value="available" <?php echo $amenity['status'] === 'available' ? 'selected' : ''; ?>>Disponible</option>
                                        <option value="occupied" <?php echo $amenity['status'] === 'occupied' ? 'selected' : ''; ?>>En uso</option>
                                        <option value="maintenance" <?php echo $amenity['status'] === 'maintenance' ? 'selected' : ''; ?>>Mantenimiento</option>
                                        <option value="blocked" <?php echo $amenity['status'] === 'blocked' ? 'selected' : ''; ?>>Bloqueada</option>
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
