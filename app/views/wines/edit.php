<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-pencil"></i> Editar Vino</h1>
    <a href="<?php echo BASE_URL; ?>/wines" class="btn btn-secondary">
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
                <form method="POST" action="<?php echo BASE_URL; ?>/wines/edit/<?php echo $wine['id']; ?>" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="<?php echo htmlspecialchars($wine['name']); ?>" required>
                        <div class="invalid-feedback">El nombre es requerido.</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($wine['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="details" class="form-label">Detalles</label>
                        <textarea class="form-control" id="details" name="details" rows="3"><?php echo htmlspecialchars($wine['details'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="suggested_for" class="form-label">Ocasiones Sugeridas</label>
                        <textarea class="form-control" id="suggested_for" name="suggested_for" rows="2"><?php echo htmlspecialchars($wine['suggested_for'] ?? ''); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Precio</label>
                            <input type="number" class="form-control" id="price" name="price"
                                   step="0.01" min="0"
                                   value="<?php echo number_format($wine['price'] ?? 0, 2, '.', ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="display_order" class="form-label">Orden de visualización</label>
                            <input type="number" class="form-control" id="display_order" name="display_order"
                                   min="0" value="<?php echo (int)($wine['display_order'] ?? 0); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image_path" class="form-label">Ruta de imagen</label>
                        <input type="text" class="form-control" id="image_path" name="image_path"
                               value="<?php echo htmlspecialchars($wine['image_path'] ?? ''); ?>"
                               placeholder="ej. amenities/wines/wine_ejemplo.png">
                        <small class="text-muted">Ruta relativa a la carpeta pública (opcional).</small>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                               <?php echo ($wine['is_active'] ?? 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="is_active">Activo (visible en el sistema)</label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Cambios
                        </button>
                        <a href="<?php echo BASE_URL; ?>/wines" class="btn btn-secondary">
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
                <p class="small mb-1"><strong>Nombre:</strong> Nombre del vino o marca.</p>
                <p class="small mb-1"><strong>Descripción:</strong> Breve descripción del vino.</p>
                <p class="small mb-1"><strong>Detalles:</strong> Color, aroma, sabor y otras características.</p>
                <p class="small mb-1"><strong>Ocasiones Sugeridas:</strong> Maridaje o momentos ideales para disfrutarlo.</p>
                <p class="small mb-1"><strong>Precio:</strong> Precio de venta al cliente.</p>
                <p class="small mb-1"><strong>Orden:</strong> Número para controlar el orden de aparición en la lista.</p>
                <p class="small mb-1"><strong>Ruta de imagen:</strong> Ruta relativa a la imagen del vino.</p>
                <p class="small mb-0"><strong>Activo:</strong> Desmarque para ocultar el vino sin eliminarlo.</p>
            </div>
        </div>
    </div>
</div>

            </div>
        </main>
    </div>
</div>
