<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-bell"></i> Solicitudes de Servicio</h1>
    <a href="<?php echo BASE_URL; ?>/services/create" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Solicitud
    </a>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (empty($requests)): ?>
    <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> No hay solicitudes de servicio
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tipo de Servicio</th>
                            <th>Descripción</th>
                            <th>Huésped</th>
                            <th>Habitación</th>
                            <th>Prioridad</th>
                            <th>Estado</th>
                            <th>Asignado a</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><?php echo $request['id']; ?></td>
                                <td><strong><?php echo $request['service_type']; ?></strong></td>
                                <td>
                                    <small>
                                        <?php echo substr($request['description'], 0, 50); ?>
                                        <?php echo strlen($request['description']) > 50 ? '...' : ''; ?>
                                    </small>
                                </td>
                                <td>
                                    <small>
                                        <?php echo $request['first_name'] . ' ' . $request['last_name']; ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if ($request['room_number']): ?>
                                        <span class="badge bg-info"><?php echo $request['room_number']; ?></span>
                                    <?php else: ?>
                                        <small class="text-muted">N/A</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge 
                                        <?php 
                                            echo $request['priority'] === 'urgent' ? 'bg-danger' : 
                                                 ($request['priority'] === 'high' ? 'bg-warning' : 
                                                  ($request['priority'] === 'medium' ? 'bg-info' : 'bg-secondary'));
                                        ?>">
                                        <?php 
                                            $priorityText = [
                                                'urgent' => 'Urgente',
                                                'high' => 'Alta',
                                                'medium' => 'Media',
                                                'low' => 'Baja'
                                            ];
                                            echo $priorityText[$request['priority']] ?? $request['priority'];
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge 
                                        <?php 
                                            echo $request['status'] === 'completed' ? 'bg-success' : 
                                                 ($request['status'] === 'pending' ? 'bg-warning' : 
                                                  ($request['status'] === 'in_progress' ? 'bg-primary' : 'bg-secondary'));
                                        ?>">
                                        <?php 
                                            $statusText = [
                                                'pending' => 'Pendiente',
                                                'assigned' => 'Asignada',
                                                'in_progress' => 'En Proceso',
                                                'completed' => 'Completada',
                                                'cancelled' => 'Cancelada'
                                            ];
                                            echo $statusText[$request['status']] ?? $request['status'];
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($request['assigned_first_name']): ?>
                                        <small>
                                            <?php echo $request['assigned_first_name'] . ' ' . $request['assigned_last_name']; ?>
                                        </small>
                                    <?php else: ?>
                                        <small class="text-muted">Sin asignar</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo BASE_URL; ?>/services/view/<?php echo $request['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <?php if (in_array($role, ['hotel_admin', 'restaurant_manager']) && $request['status'] === 'pending'): ?>
                                            <a href="<?php echo BASE_URL; ?>/services/assign/<?php echo $request['id']; ?>" 
                                               class="btn btn-sm btn-outline-success" title="Asignar">
                                                <i class="bi bi-person-plus"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($request['status'] !== 'completed' && $request['status'] !== 'cancelled'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                    data-bs-toggle="dropdown" title="Cambiar estado">
                                                <i class="bi bi-gear"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form method="POST" action="<?php echo BASE_URL; ?>/services/updateStatus/<?php echo $request['id']; ?>">
                                                        <input type="hidden" name="status" value="in_progress">
                                                        <button type="submit" class="dropdown-item">En Proceso</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" action="<?php echo BASE_URL; ?>/services/updateStatus/<?php echo $request['id']; ?>">
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="dropdown-item">Completada</button>
                                                    </form>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" action="<?php echo BASE_URL; ?>/services/updateStatus/<?php echo $request['id']; ?>">
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="dropdown-item text-danger">Cancelar</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
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
