<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-pencil"></i> Editar Habitación <?php echo $room['room_number']; ?></h1>
    <a href="<?php echo BASE_URL; ?>/rooms" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>/rooms/edit/<?php echo $room['id']; ?>" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="room_number" class="form-label">Número de Habitación *</label>
                            <input type="text" class="form-control" id="room_number" name="room_number" 
                                   value="<?php echo $room['room_number']; ?>" required>
                            <div class="invalid-feedback">El número de habitación es requerido.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="room_type" class="form-label">Tipo de Habitación *</label>
                            <select class="form-select" id="room_type" name="room_type" required>
                                <option value="">Seleccione...</option>
                                <option value="single" <?php echo $room['room_type'] === 'single' ? 'selected' : ''; ?>>Individual</option>
                                <option value="double" <?php echo $room['room_type'] === 'double' ? 'selected' : ''; ?>>Doble</option>
                                <option value="suite" <?php echo $room['room_type'] === 'suite' ? 'selected' : ''; ?>>Suite</option>
                                <option value="deluxe" <?php echo $room['room_type'] === 'deluxe' ? 'selected' : ''; ?>>Deluxe</option>
                                <option value="presidential" <?php echo $room['room_type'] === 'presidential' ? 'selected' : ''; ?>>Presidential</option>
                            </select>
                            <div class="invalid-feedback">Seleccione un tipo de habitación.</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="capacity" class="form-label">Capacidad (personas) *</label>
                            <input type="number" class="form-control" id="capacity" name="capacity" 
                                   value="<?php echo $room['capacity']; ?>" min="1" required>
                            <div class="invalid-feedback">La capacidad es requerida.</div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="price_per_night" class="form-label">Precio por Noche *</label>
                            <input type="number" class="form-control" id="price_per_night" name="price_per_night" 
                                   value="<?php echo $room['price_per_night']; ?>" step="0.01" min="0" required>
                            <div class="invalid-feedback">El precio es requerido.</div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="floor" class="form-label">Piso</label>
                            <input type="number" class="form-control" id="floor" name="floor" 
                                   value="<?php echo $room['floor'] ?? ''; ?>" min="1">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Estado *</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="available" <?php echo $room['status'] === 'available' ? 'selected' : ''; ?>>Disponible</option>
                            <option value="occupied" <?php echo $room['status'] === 'occupied' ? 'selected' : ''; ?>>Ocupada</option>
                            <option value="maintenance" <?php echo $room['status'] === 'maintenance' ? 'selected' : ''; ?>>Mantenimiento</option>
                            <option value="blocked" <?php echo $room['status'] === 'blocked' ? 'selected' : ''; ?>>Bloqueada</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo $room['description'] ?? ''; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="amenities" class="form-label">Amenidades de la Habitación</label>
                        <textarea class="form-control" id="amenities" name="amenities" rows="2"><?php echo $room['amenities'] ?? ''; ?></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Habitación
                        </button>
                        <a href="<?php echo BASE_URL; ?>/rooms" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-info-circle"></i> Información</h5>
                <p class="card-text small">
                    <strong>Creado:</strong> <?php echo date('d/m/Y H:i', strtotime($room['created_at'])); ?>
                </p>
                <p class="card-text small">
                    <strong>Última actualización:</strong> <?php echo date('d/m/Y H:i', strtotime($room['updated_at'])); ?>
                </p>
            </div>
        </div>

        <div class="card bg-warning bg-opacity-10 mt-3">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-exclamation-triangle"></i> Zona de Peligro</h5>
                <p class="card-text small">Esta acción no se puede deshacer.</p>
                <button type="button" class="btn btn-danger btn-sm w-100"
                        onclick="if(confirmDelete('¿Eliminar habitación <?php echo $room['room_number']; ?>?')) 
                                 window.location='<?php echo BASE_URL; ?>/rooms/delete/<?php echo $room['id']; ?>'">
                    <i class="bi bi-trash"></i> Eliminar Habitación
                </button>
            </div>
        </div>
    </div>
</div>

            </div>
        </main>
    </div>
</div>
