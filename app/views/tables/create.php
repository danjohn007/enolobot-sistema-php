<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-plus-circle"></i> Nueva Mesa</h1>
    <a href="<?php echo BASE_URL; ?>/tables" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>/tables/create" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="table_number" class="form-label">Número de Mesa *</label>
                            <input type="text" class="form-control" id="table_number" name="table_number" required>
                            <div class="invalid-feedback">El número de mesa es requerido.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="capacity" class="form-label">Capacidad (personas) *</label>
                            <input type="number" class="form-control" id="capacity" name="capacity" min="1" required>
                            <div class="invalid-feedback">La capacidad es requerida.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Ubicación</label>
                        <select class="form-select" id="location" name="location">
                            <option value="">Seleccione...</option>
                            <option value="Ventana">Ventana</option>
                            <option value="Centro">Centro</option>
                            <option value="Terraza">Terraza</option>
                            <option value="Salón privado">Salón privado</option>
                            <option value="Barra">Barra</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Mesa
                        </button>
                        <a href="<?php echo BASE_URL; ?>/tables" class="btn btn-secondary">
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
                <h5 class="card-title"><i class="bi bi-info-circle"></i> Ayuda</h5>
                <p class="card-text small">
                    <strong>Número de Mesa:</strong> Identificador único (ej: M1, M2, Mesa A)
                </p>
                <p class="card-text small">
                    <strong>Capacidad:</strong> Número máximo de comensales
                </p>
                <p class="card-text small">
                    <strong>Ubicación:</strong> Zona del restaurante donde se encuentra
                </p>
            </div>
        </div>
    </div>
</div>

            </div>
        </main>
    </div>
</div>
