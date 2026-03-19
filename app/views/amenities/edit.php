<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-pencil"></i> Editar Amenidad</h1>
    <a href="<?php echo BASE_URL; ?>/amenities" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-x-circle"></i> <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <form method="POST" action="<?php echo BASE_URL; ?>/amenities/edit/<?php echo $amenity['id']; ?>" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre de la Amenidad *</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="<?php echo htmlspecialchars($amenity['name']); ?>" required>
                        <div class="invalid-feedback">El nombre es requerido.</div>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Categoría</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Seleccione...</option>
                            <?php
                            $categoryOptions = ['Wellness', 'Fitness', 'Recreation', 'Business', 'Transport', 'Entertainment'];
                            foreach ($categoryOptions as $option):
                            ?>
                                <option value="<?php echo htmlspecialchars($option); ?>"
                                    <?php echo (($amenity['category'] ?? '') === $option) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($option); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($amenity['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="capacity" class="form-label">Capacidad (personas)</label>
                            <input type="number" class="form-control" id="capacity" name="capacity"
                                   value="<?php echo (int)($amenity['capacity'] ?? 1); ?>" min="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Precio</label>
                            <input type="number" class="form-control" id="price" name="price"
                                   step="0.01" min="0"
                                   value="<?php echo number_format($amenity['price'] ?? 0, 2, '.', ''); ?>">
                            <small class="text-muted">Deje en 0 si es gratuito</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="operating_hours_start" class="form-label">Horario de Inicio</label>
                            <input type="time" class="form-control" id="operating_hours_start"
                                   name="operating_hours_start"
                                   value="<?php echo htmlspecialchars(substr($amenity['operating_hours_start'] ?? '', 0, 5)); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="operating_hours_end" class="form-label">Horario de Cierre</label>
                            <input type="time" class="form-control" id="operating_hours_end"
                                   name="operating_hours_end"
                                   value="<?php echo htmlspecialchars(substr($amenity['operating_hours_end'] ?? '', 0, 5)); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="available" <?php echo (($amenity['status'] ?? '') === 'available') ? 'selected' : ''; ?>>Disponible</option>
                            <option value="occupied" <?php echo (($amenity['status'] ?? '') === 'occupied') ? 'selected' : ''; ?>>En uso</option>
                            <option value="maintenance" <?php echo (($amenity['status'] ?? '') === 'maintenance') ? 'selected' : ''; ?>>Mantenimiento</option>
                            <option value="blocked" <?php echo (($amenity['status'] ?? '') === 'blocked') ? 'selected' : ''; ?>>Bloqueada</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Cambios
                        </button>
                        <a href="<?php echo BASE_URL; ?>/amenities" class="btn btn-secondary">
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
                <h5 class="card-title"><i class="bi bi-info-circle"></i> Ejemplos de Amenidades</h5>
                <ul class="small mb-0">
                    <li>Spa y masajes</li>
                    <li>Gimnasio</li>
                    <li>Piscina</li>
                    <li>Sauna</li>
                    <li>Sala de juntas</li>
                    <li>Salón de eventos</li>
                    <li>Transporte al aeropuerto</li>
                    <li>Cancha de tenis</li>
                    <li>Área de juegos infantiles</li>
                    <li>Centro de negocios</li>
                </ul>
            </div>
        </div>
    </div>
</div>

            </div>
        </main>
    </div>
</div>
