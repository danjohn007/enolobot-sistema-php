<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-plus-circle"></i> Nuevo Platillo</h1>
    <a href="<?php echo BASE_URL; ?>/dishes" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>/dishes/create" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Categoría *</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Seleccione una categoría...</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>">
                                    <?php echo $category['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Seleccione una categoría.</div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Platillo *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback">El nombre es requerido.</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Precio *</label>
                            <input type="number" class="form-control" id="price" name="price" 
                                   step="0.01" min="0" required>
                            <div class="invalid-feedback">El precio es requerido.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="preparation_time" class="form-label">Tiempo de Preparación (min)</label>
                            <input type="number" class="form-control" id="preparation_time" name="preparation_time" 
                                   value="15" min="1">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="service_time" class="form-label">Horario de Servicio *</label>
                        <select class="form-select" id="service_time" name="service_time" required>
                            <option value="all_day">Todo el día</option>
                            <option value="breakfast">Desayuno</option>
                            <option value="lunch">Comida</option>
                            <option value="dinner">Cena</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Platillo
                        </button>
                        <a href="<?php echo BASE_URL; ?>/dishes" class="btn btn-secondary">
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
                    <strong>Categoría:</strong> Agrupa platillos similares (entradas, principales, postres)
                </p>
                <p class="card-text small">
                    <strong>Descripción:</strong> Ingredientes principales y preparación
                </p>
                <p class="card-text small">
                    <strong>Horario de Servicio:</strong> Define en qué momento del día está disponible
                </p>
            </div>
        </div>

        <div class="card mt-3 bg-warning bg-opacity-10">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-exclamation-triangle"></i> Nota</h6>
                <p class="card-text small mb-0">
                    Si no hay categorías disponibles, primero debe 
                    <a href="<?php echo BASE_URL; ?>/dishes/categories">crear categorías</a>.
                </p>
            </div>
        </div>
    </div>
</div>

            </div>
        </main>
    </div>
</div>
