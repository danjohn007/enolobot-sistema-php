<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo BASE_URL; ?>/dashboard">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    <?php if (in_array($role ?? '', ['superadmin'])): ?>
                        <li class="nav-item mt-3">
                            <h6 class="sidebar-heading px-3 text-muted">SUPERADMIN</h6>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/hotels">
                                <i class="bi bi-buildings"></i> Hoteles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/subscriptions">
                                <i class="bi bi-credit-card"></i> Suscripciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/reports/global">
                                <i class="bi bi-graph-up"></i> Métricas Globales
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (in_array($role ?? '', ['hotel_admin', 'restaurant_manager', 'hostess', 'collaborator'])): ?>
                        <?php if (in_array($role ?? '', ['hotel_admin'])): ?>
                            <li class="nav-item mt-3">
                                <h6 class="sidebar-heading px-3 text-muted">ADMINISTRACIÓN</h6>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo BASE_URL; ?>/users">
                                    <i class="bi bi-people"></i> Usuarios
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item mt-3">
                            <h6 class="sidebar-heading px-3 text-muted">HABITACIONES</h6>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/rooms">
                                <i class="bi bi-door-open"></i> Habitaciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/reservations/rooms">
                                <i class="bi bi-calendar-check"></i> Reservaciones
                            </a>
                        </li>

                        <li class="nav-item mt-3">
                            <h6 class="sidebar-heading px-3 text-muted">RESTAURANTE</h6>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/tables">
                                <i class="bi bi-table"></i> Mesas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/dishes">
                                <i class="bi bi-cup-straw"></i> Menú
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/reservations/tables">
                                <i class="bi bi-calendar-event"></i> Reservas Mesas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/orders">
                                <i class="bi bi-receipt"></i> Pedidos
                            </a>
                        </li>

                        <li class="nav-item mt-3">
                            <h6 class="sidebar-heading px-3 text-muted">SERVICIOS</h6>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/amenities">
                                <i class="bi bi-water"></i> Amenidades
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/services">
                                <i class="bi bi-bell"></i> Solicitudes
                            </a>
                        </li>

                        <?php if (in_array($role ?? '', ['hotel_admin', 'hostess'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo BASE_URL; ?>/blocks">
                                    <i class="bi bi-lock"></i> Bloqueos
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (in_array($role ?? '', ['hotel_admin'])): ?>
                            <li class="nav-item mt-3">
                            <h6 class="sidebar-heading px-3 text-muted">REPORTES</h6>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/reports">
                                <i class="bi bi-file-earmark-bar-graph"></i> Reportes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/settings">
                                <i class="bi bi-gear"></i> Configuración
                            </a>
                        </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (in_array($role ?? '', ['guest'])): ?>
                        <li class="nav-item mt-3">
                            <h6 class="sidebar-heading px-3 text-muted">HUÉSPED</h6>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/guest/rooms">
                                <i class="bi bi-door-open"></i> Buscar Habitaciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/guest/restaurant">
                                <i class="bi bi-cup-straw"></i> Menú Restaurante
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/guest/amenities">
                                <i class="bi bi-water"></i> Amenidades
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/guest/reservations">
                                <i class="bi bi-calendar-check"></i> Mis Reservaciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/guest/services">
                                <i class="bi bi-bell"></i> Solicitar Servicio
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3 pb-2 mb-3">
