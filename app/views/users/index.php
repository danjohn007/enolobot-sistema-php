<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-people"></i> Gestión de Usuarios</h1>
    <a href="<?php echo BASE_URL; ?>/users/create" class="btn btn-primary">
        <i class="bi bi-person-plus"></i> Nuevo Usuario
    </a>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (empty($users)): ?>
    <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> No hay usuarios registrados
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Último Acceso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr class="<?php echo !$user['is_active'] ? 'table-secondary' : ''; ?>">
                                <td>
                                    <strong><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></strong>
                                </td>
                                <td>
                                    <small>
                                        <i class="bi bi-envelope"></i> <?php echo $user['email']; ?>
                                    </small>
                                </td>
                                <td>
                                    <small>
                                        <?php if ($user['phone']): ?>
                                            <i class="bi bi-phone"></i> <?php echo $user['phone']; ?>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge 
                                        <?php 
                                            echo $user['role'] === 'hotel_admin' ? 'bg-primary' : 
                                                 ($user['role'] === 'restaurant_manager' ? 'bg-success' : 
                                                  ($user['role'] === 'hostess' ? 'bg-info' : 'bg-secondary'));
                                        ?>">
                                        <?php 
                                            $roleText = [
                                                'hotel_admin' => 'Admin Hotel',
                                                'restaurant_manager' => 'Gerente Rest.',
                                                'hostess' => 'Hostess',
                                                'collaborator' => 'Colaborador',
                                                'guest' => 'Huésped'
                                            ];
                                            echo $roleText[$user['role']] ?? $user['role'];
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?php echo $user['is_active'] ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo $user['is_active'] ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        <?php if ($user['last_login']): ?>
                                            <?php echo date('d/m/Y H:i', strtotime($user['last_login'])); ?>
                                        <?php else: ?>
                                            <span class="text-muted">Nunca</span>
                                        <?php endif; ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo BASE_URL; ?>/users/edit/<?php echo $user['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="<?php echo BASE_URL; ?>/users/toggleStatus/<?php echo $user['id']; ?>" 
                                                  style="display: inline;">
                                                <button type="submit" class="btn btn-sm btn-outline-warning" 
                                                        title="<?php echo $user['is_active'] ? 'Desactivar' : 'Activar'; ?>">
                                                    <i class="bi bi-<?php echo $user['is_active'] ? 'toggle-on' : 'toggle-off'; ?>"></i>
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge bg-info">Tú</span>
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
