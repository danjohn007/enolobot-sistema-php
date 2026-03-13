<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-table"></i> Gestión de Mesas</h1>
    <?php if (in_array($role, ['hotel_admin', 'restaurant_manager'])): ?>
        <a href="<?php echo BASE_URL; ?>/tables/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Mesa
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
    <?php if (empty($tables)): ?>
        <div class="col-md-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> No hay mesas registradas
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($tables as $table): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-table"></i> Mesa <?php echo $table['table_number']; ?></h5>
                        <span class="badge badge-status 
                            <?php 
                                echo $table['status'] === 'available' ? 'bg-success' : 
                                     ($table['status'] === 'occupied' ? 'bg-danger' : 
                                      ($table['status'] === 'reserved' ? 'bg-warning' : 'bg-secondary'));
                            ?>">
                            <?php 
                                $statusText = [
                                    'available' => 'Disponible',
                                    'occupied' => 'Ocupada',
                                    'reserved' => 'Reservada',
                                    'blocked' => 'Bloqueada'
                                ];
                                echo $statusText[$table['status']] ?? $table['status'];
                            ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <strong><i class="bi bi-people"></i> Capacidad:</strong> <?php echo $table['capacity']; ?> personas
                        </p>
                        <?php if ($table['location']): ?>
                            <p class="card-text">
                                <strong><i class="bi bi-geo-alt"></i> Ubicación:</strong> <?php echo $table['location']; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="btn-group w-100" role="group">
                            <?php if (in_array($role, ['hotel_admin', 'restaurant_manager'])): ?>
                                <a href="<?php echo BASE_URL; ?>/tables/edit/<?php echo $table['id']; ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                            <?php endif; ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                    data-bs-toggle="modal" data-bs-target="#statusModal<?php echo $table['id']; ?>">
                                <i class="bi bi-gear"></i> Estado
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Change Modal -->
            <div class="modal fade" id="statusModal<?php echo $table['id']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cambiar Estado - Mesa <?php echo $table['table_number']; ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="<?php echo BASE_URL; ?>/tables/changeStatus/<?php echo $table['id']; ?>">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nuevo Estado</label>
                                    <select class="form-select" name="status" required>
                                        <option value="available" <?php echo $table['status'] === 'available' ? 'selected' : ''; ?>>Disponible</option>
                                        <option value="occupied" <?php echo $table['status'] === 'occupied' ? 'selected' : ''; ?>>Ocupada</option>
                                        <option value="reserved" <?php echo $table['status'] === 'reserved' ? 'selected' : ''; ?>>Reservada</option>
                                        <option value="blocked" <?php echo $table['status'] === 'blocked' ? 'selected' : ''; ?>>Bloqueada</option>
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
