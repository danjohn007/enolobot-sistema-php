<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-cup-hot"></i> Vinos</h1>
    <?php if ($role === 'hotel_admin'): ?>
        <a href="<?php echo BASE_URL; ?>/wines/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Agregar Vino
        </a>
    <?php endif; ?>
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

<?php if (empty($wines)): ?>
    <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> No hay vinos registrados
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>NOMBRE</th>
                            <th>DESCRIPCIÓN</th>
                            <th>PRECIO</th>
                            <?php if ($role === 'hotel_admin'): ?>
                                <th>ACCIONES</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($wines as $wine): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($wine['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($wine['description'] ?? '—'); ?></td>
                                <td>$<?php echo number_format($wine['price'] ?? 0, 2); ?></td>
                                <?php if ($role === 'hotel_admin'): ?>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/wines/delete/<?php echo $wine['id']; ?>"
                                           class="btn btn-sm btn-danger" title="Eliminar"
                                           onclick="return confirm('¿Eliminar este vino?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                <?php endif; ?>
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
