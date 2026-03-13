<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-plus-circle"></i> Nueva Habitación</h1>
    <a href="<?php echo BASE_URL; ?>/rooms" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>/rooms/create" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="room_number" class="form-label">Número de Habitación *</label>
                            <input type="text" class="form-control" id="room_number" name="room_number" required>
                            <div class="invalid-feedback">El número de habitación es requerido.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="room_type" class="form-label">Tipo de Habitación *</label>
                            <select class="form-select" id="room_type" name="room_type" required>
                                <option value="">Seleccione...</option>
                                <option value="single">Individual</option>
                                <option value="double">Doble</option>
                                <option value="suite">Suite</option>
                                <option value="deluxe">Deluxe</option>
                                <option value="presidential">Presidential</option>
                            </select>
                            <div class="invalid-feedback">Seleccione un tipo de habitación.</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="capacity" class="form-label">Capacidad (personas) *</label>
                            <input type="number" class="form-control" id="capacity" name="capacity" min="1" required>
                            <div class="invalid-feedback">La capacidad es requerida.</div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="price_per_night" class="form-label">Precio por Noche *</label>
                            <input type="number" class="form-control" id="price_per_night" name="price_per_night" 
                                   step="0.01" min="0" required>
                            <div class="invalid-feedback">El precio es requerido.</div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="floor" class="form-label">Piso</label>
                            <input type="number" class="form-control" id="floor" name="floor" min="1">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="amenities" class="form-label">Amenidades de la Habitación</label>
                        <textarea class="form-control" id="amenities" name="amenities" rows="2" 
                                  placeholder="WiFi, TV, Aire acondicionado, Mini bar..."></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Habitación
                        </button>
                        <a href="<?php echo BASE_URL; ?>/rooms" class="btn btn-secondary">
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
                    <strong>Número de Habitación:</strong> Identificador único para la habitación (ej: 101, 201, etc.)
                </p>
                <p class="card-text small">
                    <strong>Tipo:</strong> Categoría de la habitación según su capacidad y servicios.
                </p>
                <p class="card-text small">
                    <strong>Capacidad:</strong> Número máximo de huéspedes permitidos.
                </p>
                <p class="card-text small">
                    <strong>Precio:</strong> Tarifa por noche en la moneda del hotel.
                </p>
            </div>
        </div>
    </div>
</div>

            </div>
        </main>
    </div>
</div>
