<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-plus-circle"></i> Nueva Solicitud de Servicio</h1>
    <a href="<?php echo BASE_URL; ?>/services" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>/services/create" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="service_type" class="form-label">Tipo de Servicio *</label>
                        <select class="form-select" id="service_type" name="service_type" required>
                            <option value="">Seleccione...</option>
                            <option value="Limpieza">Limpieza</option>
                            <option value="Room Service">Room Service</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                            <option value="Toallas">Toallas adicionales</option>
                            <option value="Blancos">Ropa de cama</option>
                            <option value="Amenidades">Amenidades de baño</option>
                            <option value="Despertador">Servicio despertador</option>
                            <option value="Transporte">Transporte</option>
                            <option value="Conserjería">Conserjería</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <div class="invalid-feedback">Seleccione el tipo de servicio.</div>
                    </div>

                    <?php if (!empty($rooms)): ?>
                        <div class="mb-3">
                            <label for="room_id" class="form-label">Habitación</label>
                            <select class="form-select" id="room_id" name="room_id">
                                <option value="">Seleccione...</option>
                                <?php foreach ($rooms as $room): ?>
                                    <option value="<?php echo $room['id']; ?>">
                                        Habitación <?php echo $room['room_number']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción *</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="4" required></textarea>
                        <div class="invalid-feedback">La descripción es requerida.</div>
                    </div>

                    <div class="mb-3">
                        <label for="priority" class="form-label">Prioridad *</label>
                        <select class="form-select" id="priority" name="priority" required>
                            <option value="low">Baja</option>
                            <option value="medium" selected>Media</option>
                            <option value="high">Alta</option>
                            <option value="urgent">Urgente</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Enviar Solicitud
                        </button>
                        <a href="<?php echo BASE_URL; ?>/services" class="btn btn-secondary">
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
                <h5 class="card-title"><i class="bi bi-info-circle"></i> Prioridades</h5>
                <ul class="small mb-0">
                    <li><span class="badge bg-secondary">Baja:</span> Sin urgencia</li>
                    <li><span class="badge bg-info">Media:</span> Atención en horario normal</li>
                    <li><span class="badge bg-warning">Alta:</span> Requiere atención pronta</li>
                    <li><span class="badge bg-danger">Urgente:</span> Atención inmediata</li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-clock"></i> Tiempo de Respuesta</h6>
                <p class="card-text small mb-0">
                    Las solicitudes son atendidas según su prioridad. 
                    Recibirá una notificación cuando su solicitud sea asignada y completada.
                </p>
            </div>
        </div>
    </div>
</div>

            </div>
        </main>
    </div>
</div>
