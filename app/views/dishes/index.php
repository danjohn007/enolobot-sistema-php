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

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-x-circle"></i> <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
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

<?php
$serviceTimeText = [
    'breakfast' => 'Desayuno',
    'lunch'     => 'Comida',
    'dinner'    => 'Cena',
    'all_day'   => 'Todo el día',
];
?>

<?php if (empty($dishes)): ?>
    <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> No hay platillos registrados en el menú
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>NOMBRE</th>
                            <th>CATEGORÍA</th>
                            <th>PRECIO</th>
                            <th>SERVICIO</th>
                            <th>DISPONIBLE</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dishes as $dish): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($dish['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($dish['category_name'] ?? $dish['category'] ?? '—'); ?></td>
                                <td><strong>$<?php echo number_format($dish['price'], 2); ?></strong></td>
                                <td><?php echo $serviceTimeText[$dish['service_time']] ?? htmlspecialchars($dish['service_time'] ?? '—'); ?></td>
                                <td>
                                    <?php if ($dish['is_available']): ?>
                                        <span class="badge bg-success">Sí</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">No</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>/dishes/edit/<?php echo $dish['id']; ?>"
                                       class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="<?php echo BASE_URL; ?>/dishes/toggleAvailability/<?php echo $dish['id']; ?>"
                                          style="display: inline;">
                                        <button type="submit" class="btn btn-sm btn-secondary" title="Alternar disponibilidad">
                                            <i class="bi bi-toggle-on"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" title="Eliminar"
                                            onclick="if(confirm('¿Eliminar platillo?')) window.location='<?php echo BASE_URL; ?>/dishes/delete/<?php echo $dish['id']; ?>'">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
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
