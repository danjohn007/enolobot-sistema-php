<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-plus-circle"></i> Agregar Vino</h1>
    <a href="<?php echo BASE_URL; ?>/wines" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>/wines/create" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback">El nombre es requerido.</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="details" class="form-label">Detalles</label>
                        <textarea class="form-control" id="details" name="details" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="suggested_for" class="form-label">Ocasiones Sugeridas</label>
                        <textarea class="form-control" id="suggested_for" name="suggested_for" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="price" name="price"
                               step="0.01" min="0" value="0">
                        <small class="text-muted">Deje en 0 si es sin costo</small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Vino
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
                <p class="small mb-0"><strong>Precio:</strong> Precio de venta al cliente.</p>
            </div>
        </div>
    </div>
</div>

            </div>
        </main>
    </div>
</div>
