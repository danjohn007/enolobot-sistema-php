<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-cup-hot"></i> Gestión de Vinos</h1>
    <?php if (in_array($role, ['hotel_admin', 'superadmin'])): ?>
        <a href="<?php echo BASE_URL; ?>/wines/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Vino
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
                            <th>IMAGEN</th>
                            <th>NOMBRE</th>
                            <th>DESCRIPCIÓN</th>
                            <th>PRECIO</th>
                            <th>DISPONIBLE</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($wines as $wine): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($wine['image_path'])): ?>
                                        <img src="<?php echo BASE_URL . '/' . htmlspecialchars($wine['image_path']); ?>"
                                             alt="<?php echo htmlspecialchars($wine['name']); ?>"
                                             style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                    <?php else: ?>
                                        <div style="width:50px;height:50px;background:#e9ecef;border-radius:4px;display:flex;align-items:center;justify-content:center;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($wine['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($wine['description'] ?? '—'); ?></td>
                                <td>$<?php echo number_format($wine['price'] ?? 0, 2); ?></td>
                                <td>
                                    <?php if (($wine['is_active'] ?? 0) == 1): ?>
                                        <span class="badge bg-success">Sí</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">No</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (in_array($role, ['hotel_admin', 'superadmin'])): ?>
                                        <a href="<?php echo BASE_URL; ?>/wines/edit/<?php echo $wine['id']; ?>"
                                           class="btn btn-sm btn-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>/wines/delete/<?php echo $wine['id']; ?>"
                                           class="btn btn-sm btn-danger" title="Eliminar"
                                           onclick="return confirm('¿Eliminar este vino?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php endif; ?>
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
