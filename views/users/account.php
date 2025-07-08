<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body style="min-height: 100vh; display: flex; flex-direction: column;">
<?php include 'views/layouts/header.php'; ?>
<div class="container mt-5 flex-grow-1">
    <h2 class="mb-4">Configurar cuenta</h2>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
                echo htmlspecialchars($_SESSION['error_message']);
                unset($_SESSION['error_message']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['mensaje_exito'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
                echo htmlspecialchars($_SESSION['mensaje_exito']);
                unset($_SESSION['mensaje_exito']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['mensaje_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
                echo htmlspecialchars($_SESSION['mensaje_error']);
                unset($_SESSION['mensaje_error']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="post" action="index.php?controller=UserController&action=updateAccount" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" 
                   class="form-control" 
                   id="nombre" 
                   name="nombre" 
                   value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" 
                   required>
            <div class="invalid-feedback">
                Por favor ingresa tu nombre.
            </div>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" 
                   class="form-control" 
                   id="email" 
                   name="email" 
                   value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" 
                   required>
            <div class="invalid-feedback">
                Por favor ingresa un correo electrónico válido.
            </div>
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" 
                   class="form-control" 
                   id="direccion" 
                   name="direccion" 
                   value="<?php echo htmlspecialchars($usuario['direccion'] ?? ''); ?>">
            <div class="form-text">Necesaria para realizar pedidos.</div>
        </div>
        <div class="mb-3">
            <label for="pais" class="form-label">País</label>
            <input type="text" 
                   class="form-control" 
                   id="pais" 
                   name="pais" 
                   value="<?php echo htmlspecialchars($usuario['pais'] ?? ''); ?>">
        </div>

        <div class="mb-3">
            <label for="ciudad" class="form-label">Ciudad</label>
            <input type="text" 
                   class="form-control" 
                   id="ciudad" 
                   name="ciudad" 
                   value="<?php echo htmlspecialchars($usuario['ciudad'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label for="codigo_postal" class="form-label">Código Postal</label>
            <input type="text" 
                   class="form-control" 
                   id="codigo_postal" 
                   name="codigo_postal" 
                   value="<?php echo htmlspecialchars($usuario['codigo_postal'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label for="contacto" class="form-label">Contacto</label>
            <input type="text" 
                   class="form-control" 
                   id="contacto" 
                   name="contacto" 
                   value="<?php echo htmlspecialchars($usuario['contacto'] ?? ''); ?>">
            <div class="form-text">Número de teléfono o forma de contacto preferida.</div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar cambios
            </button>
            
            <?php if (isset($_SESSION['redirect_after_update']) && $_SESSION['redirect_after_update'] === 'checkout'): ?>
                <a href="index.php?controller=CartController&action=index" class="btn btn-secondary">
                    <i class="fas fa-shopping-cart"></i> Volver al carrito
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div style="margin-top: auto;">
    <?php include 'views/layouts/footer.php'; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
// Validación del formulario usando Bootstrap
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>
</body>
</html> 