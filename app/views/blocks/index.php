<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-lock"></i> Gestión de Bloqueos</h1>
    <div>
        <a href="<?php echo BASE_URL; ?>/blocks/history" class="btn btn-secondary me-2">
            <i class="bi bi-clock-history"></i> Historial
        </a>
        <a href="<?php echo BASE_URL; ?>/blocks/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Bloqueo
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (empty($blocks)): ?>
    <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> No hay bloqueos activos
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Recurso</th>
                            <th>Motivo</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Bloqueado Por</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($blocks as $block): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?php 
                                            $typeText = [
                                                'room' => 'Habitación',
                                                'table' => 'Mesa',
                                                'amenity' => 'Amenidad'
                                            ];
                                            echo $typeText[$block['resource_type']] ?? $block['resource_type'];
                                        ?>
                                    </span>
                                </td>
                                <td><strong><?php echo $block['resource_name']; ?></strong></td>
                                <td><?php echo $block['reason']; ?></td>
                                <td>
                                    <small>
                                        <i class="bi bi-calendar"></i>
                                        <?php echo date('d/m/Y H:i', strtotime($block['start_datetime'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if ($block['end_datetime']): ?>
                                        <small>
                                            <i class="bi bi-calendar"></i>
                                            <?php echo date('d/m/Y H:i', strtotime($block['end_datetime'])); ?>
                                        </small>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Indefinido</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small>
                                        <?php echo $block['first_name'] . ' ' . $block['last_name']; ?>
                                    </small>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-success"
                                            onclick="if(confirm('¿Liberar este bloqueo?')) 
                                                     window.location='<?php echo BASE_URL; ?>/blocks/release/<?php echo $block['id']; ?>'">
                                        <i class="bi bi-unlock"></i> Liberar
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

<div class="mt-4">
    <div class="card bg-light">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-info-circle"></i> Información sobre Bloqueos</h5>
            <p class="card-text small mb-2">
                <strong>Tipos de Bloqueo:</strong>
            </p>
            <ul class="small mb-0">
                <li><strong>Mantenimiento:</strong> Para reparaciones o limpieza profunda</li>
                <li><strong>Evento:</strong> Reservado para eventos especiales</li>
                <li><strong>Temporal:</strong> Bloqueo temporal con fecha de finalización</li>
                <li><strong>Indefinido:</strong> Sin fecha de finalización, requiere liberación manual</li>
            </ul>
        </div>
    </div>
</div>

            </div>
        </main>
    </div>
</div>
