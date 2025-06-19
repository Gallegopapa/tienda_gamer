<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body style="min-height: 100vh; display: flex; flex-direction: column;">
<?php include 'views/layouts/header.php'; ?>
<div class="container mt-5 flex-grow-1">
    <h2 class="mb-4">Configurar cuenta</h2>
    <?php if (isset($mensaje_exito)): ?>
        <div class="alert alert-success"><?php echo $mensaje_exito; ?></div>
    <?php elseif (isset($mensaje_error)): ?>
        <div class="alert alert-danger"><?php echo $mensaje_error; ?></div>
    <?php endif; ?>
    <form method="post" action="index.php?controller=UserController&action=updateAccount">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
        </div>

        <!-- <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion']); ?>">
        </div> -->
        <!-- <div class="mb-3">
            <label for="pais" class="form-label">País</label>
            <input type="text" class="form-control" id="pais" name="pais" value="<?php echo htmlspecialchars($usuario['pais']); ?>">
        </div> -->

        <div class="mb-3">
            <label for="ciudad" class="form-label">Ciudad</label>
            <input type="text" class="form-control" id="ciudad" name="ciudad" value="<?php echo htmlspecialchars($usuario['ciudad']); ?>">
        </div>
        <div class="mb-3">
            <label for="codigo_postal" class="form-label">Código Postal</label>
            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" value="<?php echo htmlspecialchars($usuario['codigo_postal']); ?>">
        </div>
        <div class="mb-3">
            <label for="contacto" class="form-label">Contacto</label>
            <input type="text" class="form-control" id="contacto" name="contacto" value="<?php echo htmlspecialchars($usuario['contacto']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </form>
</div>
<div style="margin-top: auto;">
<?php include 'views/layouts/footer.php'; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 