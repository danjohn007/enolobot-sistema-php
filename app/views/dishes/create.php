<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-3"><i class="bi bi-plus-circle"></i> Nuevo Platillo</h5>
        <hr>
        <form method="POST" action="<?php echo BASE_URL; ?>/dishes/create" class="needs-validation" novalidate>
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="name" class="form-label">Nombre del Platillo *</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="invalid-feedback">El nombre es requerido.</div>
                </div>
                <div class="col-md-4">
                    <label for="price" class="form-label">Precio *</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control" id="price" name="price"
                               step="0.01" min="0" required>
                    </div>
                    <div class="invalid-feedback">El precio es requerido.</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="category" class="form-label">Categoría *</label>
                    <select class="form-select" id="category" name="category" required>
                        <option value="">Seleccionar...</option>
                        <option value="Entrada">Entrada</option>
                        <option value="Plato Principal">Plato Principal</option>
                        <option value="Postre">Postre</option>
                        <option value="Bebida">Bebida</option>
                        <option value="Desayuno">Desayuno</option>
                        <option value="Comida">Comida</option>
                        <option value="Cena">Cena</option>
                        <option value="Sopa">Sopa</option>
                        <option value="Pasta">Pasta</option>
                        <option value="Ensalada">Ensalada</option>
                        <option value="Especialidad">Especialidad</option>
                    </select>
                    <div class="invalid-feedback">Seleccione una categoría.</div>
                </div>
                <div class="col-md-6">
                    <label for="service_time" class="form-label">Tiempo de Servicio</label>
                    <select class="form-select" id="service_time" name="service_time">
                        <option value="all_day">Todo el día</option>
                        <option value="breakfast">Desayuno</option>
                        <option value="lunch">Comida</option>
                        <option value="dinner">Cena</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" rows="4"></textarea>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_available" name="is_available" checked>
                <label class="form-check-label" for="is_available">Disponible para ordenar</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Guardar
                </button>
                <a href="<?php echo BASE_URL; ?>/dishes" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

            </div>
        </main>
    </div>
</div>
