<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-3"><i class="bi bi-pencil"></i> Editar Platillo</h5>
        <hr>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-x-circle"></i> <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <form method="POST" action="<?php echo BASE_URL; ?>/dishes/edit/<?php echo $dish['id']; ?>" class="needs-validation" novalidate>
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="name" class="form-label">Nombre del Platillo *</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="<?php echo htmlspecialchars($dish['name']); ?>" required>
                    <div class="invalid-feedback">El nombre es requerido.</div>
                </div>
                <div class="col-md-4">
                    <label for="price" class="form-label">Precio *</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control" id="price" name="price"
                               step="0.01" min="0"
                               value="<?php echo number_format($dish['price'], 2, '.', ''); ?>" required>
                    </div>
                    <div class="invalid-feedback">El precio es requerido.</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="category" class="form-label">Categoría *</label>
                    <select class="form-select" id="category" name="category" required>
                        <option value="">Seleccionar...</option>
                        <?php
                        $categoryOptions = ['Entrada', 'Plato Principal', 'Postre', 'Bebida', 'Desayuno', 'Comida', 'Cena', 'Sopa', 'Pasta', 'Ensalada', 'Especialidad'];
                        $currentCategory = $dish['category_name'] ?? $dish['category'] ?? '';
                        foreach ($categoryOptions as $option):
                        ?>
                            <option value="<?php echo htmlspecialchars($option); ?>"
                                <?php echo ($currentCategory === $option) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($option); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Seleccione una categoría.</div>
                </div>
                <div class="col-md-6">
                    <label for="service_time" class="form-label">Tiempo de Servicio</label>
                    <select class="form-select" id="service_time" name="service_time">
                        <option value="all_day" <?php echo ($dish['service_time'] === 'all_day') ? 'selected' : ''; ?>>Todo el día</option>
                        <option value="breakfast" <?php echo ($dish['service_time'] === 'breakfast') ? 'selected' : ''; ?>>Desayuno</option>
                        <option value="lunch" <?php echo ($dish['service_time'] === 'lunch') ? 'selected' : ''; ?>>Comida</option>
                        <option value="dinner" <?php echo ($dish['service_time'] === 'dinner') ? 'selected' : ''; ?>>Cena</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($dish['description'] ?? ''); ?></textarea>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_available" name="is_available"
                       <?php echo $dish['is_available'] ? 'checked' : ''; ?>>
                <label class="form-check-label" for="is_available">Disponible para ordenar</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Actualizar
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
