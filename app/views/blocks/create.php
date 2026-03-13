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
                    <div class="mb-3">
                        <label for="resource_type" class="form-label">Tipo de Recurso *</label>
                        <select class="form-select" id="resource_type" name="resource_type" required onchange="updateResourceList()">
                            <option value="">Seleccione...</option>
                            <option value="room">Habitación</option>
                            <option value="table">Mesa</option>
                            <option value="amenity">Amenidad</option>
                        </select>
                        <div class="invalid-feedback">Seleccione el tipo de recurso.</div>
                    </div>

                    <div class="mb-3" id="resource_list" style="display: none;">
                        <label for="resource_id" class="form-label">Recurso a Bloquear *</label>
                        <select class="form-select" id="resource_id" name="resource_id" required>
                            <option value="">Seleccione primero el tipo de recurso...</option>
                        </select>
                        <div class="invalid-feedback">Seleccione el recurso.</div>
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
                    <strong>Nota:</strong> El recurso bloqueado cambiará su estado automáticamente y no estará disponible para reservas.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
const rooms = <?php echo json_encode($rooms); ?>;
const tables = <?php echo json_encode($tables); ?>;
const amenities = <?php echo json_encode($amenities); ?>;

function updateResourceList() {
    const resourceType = document.getElementById('resource_type').value;
    const resourceSelect = document.getElementById('resource_id');
    const resourceDiv = document.getElementById('resource_list');
    
    resourceSelect.innerHTML = '<option value="">Seleccione...</option>';
    
    if (resourceType) {
        resourceDiv.style.display = 'block';
        let resources = [];
        
        if (resourceType === 'room') {
            resources = rooms;
            resources.forEach(r => {
                resourceSelect.innerHTML += `<option value="${r.id}">Habitación ${r.room_number} - ${r.room_type}</option>`;
            });
        } else if (resourceType === 'table') {
            resources = tables;
            resources.forEach(t => {
                resourceSelect.innerHTML += `<option value="${t.id}">Mesa ${t.table_number} - Cap: ${t.capacity}</option>`;
            });
        } else if (resourceType === 'amenity') {
            resources = amenities;
            resources.forEach(a => {
                resourceSelect.innerHTML += `<option value="${a.id}">${a.name}</option>`;
            });
        }
    } else {
        resourceDiv.style.display = 'none';
    }
}

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
