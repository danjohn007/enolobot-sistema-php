<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-lock"></i> Nuevo Bloqueo</h1>
    <a href="<?php echo BASE_URL; ?>/blocks" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>/blocks/create" class="needs-validation" novalidate>
                    <input type="hidden" name="resource_type" value="amenity">

                    <div class="mb-3">
                        <label for="resource_id" class="form-label">Amenidad a Bloquear *</label>
                        <select class="form-select" id="resource_id" name="resource_id" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($amenities as $amenity): ?>
                                <option value="<?php echo $amenity['id']; ?>"><?php echo $amenity['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Seleccione la amenidad.</div>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Motivo del Bloqueo *</label>
                        <select class="form-select" id="reason" name="reason" required>
                            <option value="">Seleccione...</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                            <option value="Limpieza profunda">Limpieza profunda</option>
                            <option value="Reparación">Reparación</option>
                            <option value="Evento especial">Evento especial</option>
                            <option value="Renovación">Renovación</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <div class="invalid-feedback">El motivo es requerido.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_datetime" class="form-label">Fecha y Hora de Inicio *</label>
                            <input type="datetime-local" class="form-control" id="start_datetime" 
                                   name="start_datetime" required>
                            <div class="invalid-feedback">La fecha de inicio es requerida.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_datetime" class="form-label">Fecha y Hora de Fin</label>
                            <input type="datetime-local" class="form-control" id="end_datetime" 
                                   name="end_datetime">
                            <small class="text-muted">Dejar vacío para bloqueo indefinido</small>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-lock"></i> Crear Bloqueo
                        </button>
                        <a href="<?php echo BASE_URL; ?>/blocks" class="btn btn-secondary">
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
                    <strong>Bloqueo Temporal:</strong> Especifique fecha de inicio y fin para que se libere automáticamente.
                </p>
                <p class="card-text small">
                    <strong>Bloqueo Indefinido:</strong> No especifique fecha de fin. Deberá liberarse manualmente.
                </p>
                <p class="card-text small mb-0">
                    <strong>Nota:</strong> La amenidad bloqueada cambiará su estado automáticamente y no estará disponible.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Set minimum date to today
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.getElementById('start_datetime').min = now.toISOString().slice(0, 16);
});
</script>

            </div>
        </main>
    </div>
</div>
