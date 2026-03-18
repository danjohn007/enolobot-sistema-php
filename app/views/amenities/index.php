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

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-x-circle"></i> <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (empty($amenities)): ?>
    <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> No hay amenidades registradas
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>IMAGEN</th>
                            <th>NOMBRE</th>
                            <th>CATEGORÍA</th>
                            <th>PRECIO</th>
                            <th>CAPACIDAD</th>
                            <th>HORARIO</th>
                            <th>DISPONIBLE</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($amenities as $amenity): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($amenity['image'])): ?>
                                        <img src="<?php echo BASE_URL . '/' . htmlspecialchars($amenity['image']); ?>"
                                             alt="<?php echo htmlspecialchars($amenity['name']); ?>"
                                             style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                    <?php else: ?>
                                        <div style="width:50px;height:50px;background:#e9ecef;border-radius:4px;display:flex;align-items:center;justify-content:center;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($amenity['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($amenity['category'] ?? '—'); ?></td>
                                <td>$<?php echo number_format($amenity['price'] ?? 0, 2); ?></td>
                                <td><?php echo (int)($amenity['capacity'] ?? 1); ?></td>
                                <td>
                                    <?php if (!empty($amenity['operating_hours_start']) && !empty($amenity['operating_hours_end'])): ?>
                                        <?php echo date('H:i', strtotime($amenity['operating_hours_start'])); ?> -
                                        <?php echo date('H:i', strtotime($amenity['operating_hours_end'])); ?>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (($amenity['is_active'] ?? 0) == 1): ?>
                                        <span class="badge bg-success">Sí</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">No</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($role === 'hotel_admin'): ?>
                                        <a href="<?php echo BASE_URL; ?>/amenities/edit/<?php echo $amenity['id']; ?>"
                                           class="btn btn-sm btn-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-sm btn-secondary"
                                            data-bs-toggle="modal" data-bs-target="#statusModal<?php echo $amenity['id']; ?>"
                                            title="Cambiar estado">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                    <?php if ($role === 'hotel_admin'): ?>
                                        <a href="<?php echo BASE_URL; ?>/amenities/delete/<?php echo $amenity['id']; ?>"
                                           class="btn btn-sm btn-danger" title="Eliminar"
                                           onclick="return confirm('¿Eliminar esta amenidad?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <!-- Status Change Modal -->
                            <div class="modal fade" id="statusModal<?php echo $amenity['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Cambiar Estado - <?php echo htmlspecialchars($amenity['name']); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="<?php echo BASE_URL; ?>/amenities/changeStatus/<?php echo $amenity['id']; ?>">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nuevo Estado</label>
                                                    <select class="form-select" name="status" required>
                                                        <option value="available" <?php echo ($amenity['status'] ?? '') === 'available' ? 'selected' : ''; ?>>Disponible</option>
                                                        <option value="occupied" <?php echo ($amenity['status'] ?? '') === 'occupied' ? 'selected' : ''; ?>>En uso</option>
                                                        <option value="maintenance" <?php echo ($amenity['status'] ?? '') === 'maintenance' ? 'selected' : ''; ?>>Mantenimiento</option>
                                                        <option value="blocked" <?php echo ($amenity['status'] ?? '') === 'blocked' ? 'selected' : ''; ?>>Bloqueada</option>
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

            </div>
        </main>
    </div>
</div>
