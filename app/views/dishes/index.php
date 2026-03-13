<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-cup-straw"></i> Gestión de Menú</h1>
    <div>
        <a href="<?php echo BASE_URL; ?>/dishes/categories" class="btn btn-secondary me-2">
            <i class="bi bi-list"></i> Categorías
        </a>
        <a href="<?php echo BASE_URL; ?>/dishes/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Platillo
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (empty($categories)): ?>
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i> 
        Debe crear al menos una categoría antes de agregar platillos. 
        <a href="<?php echo BASE_URL; ?>/dishes/categories" class="alert-link">Crear categorías</a>
    </div>
<?php endif; ?>

<?php if (empty($dishes)): ?>
    <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> No hay platillos registrados en el menú
    </div>
<?php else: ?>
    <?php 
    $dishesByCategory = [];
    foreach ($dishes as $dish) {
        $catName = $dish['category_name'] ?? 'Sin categoría';
        if (!isset($dishesByCategory[$catName])) {
            $dishesByCategory[$catName] = [];
        }
        $dishesByCategory[$catName][] = $dish;
    }
    ?>

    <?php foreach ($dishesByCategory as $categoryName => $categoryDishes): ?>
        <h4 class="mt-4 mb-3"><?php echo $categoryName; ?></h4>
        <div class="row g-4 mb-4">
            <?php foreach ($categoryDishes as $dish): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title"><?php echo $dish['name']; ?></h5>
                                <span class="badge <?php echo $dish['is_available'] ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $dish['is_available'] ? 'Disponible' : 'Agotado'; ?>
                                </span>
                            </div>
                            <?php if ($dish['description']): ?>
                                <p class="card-text text-muted small"><?php echo $dish['description']; ?></p>
                            <?php endif; ?>
                            <p class="card-text">
                                <strong class="text-primary" style="font-size: 1.25rem;">
                                    $<?php echo number_format($dish['price'], 2); ?>
                                </strong>
                            </p>
                            <p class="card-text small">
                                <i class="bi bi-clock"></i> <?php echo $dish['preparation_time']; ?> min
                                <span class="ms-2">
                                    <i class="bi bi-sun"></i> 
                                    <?php 
                                        $serviceTimeText = [
                                            'breakfast' => 'Desayuno',
                                            'lunch' => 'Comida',
                                            'dinner' => 'Cena',
                                            'all_day' => 'Todo el día'
                                        ];
                                        echo $serviceTimeText[$dish['service_time']] ?? $dish['service_time'];
                                    ?>
                                </span>
                            </p>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="btn-group w-100" role="group">
                                <a href="<?php echo BASE_URL; ?>/dishes/edit/<?php echo $dish['id']; ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form method="POST" action="<?php echo BASE_URL; ?>/dishes/toggleAvailability/<?php echo $dish['id']; ?>" 
                                      style="display: inline;">
                                    <button type="submit" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-toggle-on"></i> 
                                        <?php echo $dish['is_available'] ? 'Agotar' : 'Disponible'; ?>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="if(confirmDelete('¿Eliminar platillo?')) 
                                                 window.location='<?php echo BASE_URL; ?>/dishes/delete/<?php echo $dish['id']; ?>'">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

            </div>
        </main>
    </div>
</div>
