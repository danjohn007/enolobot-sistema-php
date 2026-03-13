    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="bi bi-building"></i> MajorBot</h5>
                    <p>Sistema completo de mayordomía online para hoteles modernos.</p>
                </div>
                <div class="col-md-4">
                    <h5>Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo BASE_URL; ?>" class="text-white-50">Inicio</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/about" class="text-white-50">Acerca de</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/contact" class="text-white-50">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contacto</h5>
                    <p class="text-white-50">
                        <i class="bi bi-envelope"></i> info@majorbot.com<br>
                        <i class="bi bi-phone"></i> +52 (55) 1234-5678
                    </p>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> MajorBot. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Confirm delete actions
        function confirmDelete(message) {
            return confirm(message || '¿Está seguro de que desea eliminar este elemento?');
        }
    </script>
</body>
</html>
