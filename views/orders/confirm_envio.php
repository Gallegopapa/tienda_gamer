<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>
<div class="container mt-5 main-content" style="max-width: 600px;">
    <h2 class="mb-4">Confirmar dirección de envío</h2>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">¿Desea que el pedido se envíe a esta dirección?</h5>
            <p class="mb-1"><strong>Dirección:</strong> <?php echo htmlspecialchars($datos_envio['direccion']); ?></p>
            <p class="mb-1"><strong>País:</strong> <?php echo htmlspecialchars($datos_envio['pais']); ?></p>
            <p class="mb-1"><strong>Ciudad:</strong> <?php echo htmlspecialchars($datos_envio['ciudad']); ?></p>
            <p class="mb-1"><strong>Código Postal:</strong> <?php echo htmlspecialchars($datos_envio['codigo_postal']); ?></p>
            <p class="mb-1"><strong>Contacto:</strong> <?php echo htmlspecialchars($datos_envio['contacto']); ?></p>
        </div>
    </div>
    <form method="post">
        <input type="hidden" name="confirmar_envio" value="1">
        <div class="mb-3">
            <label for="metodo_pago" class="form-label">Método de pago</label>
            <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                <option value="Tarjeta">Tarjeta</option>
                <option value="Transferencia">Transferencia</option>
                <option value="Contra reembolso">Contra reembolso</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Sí, confirmar y finalizar pedido</button>
        <button type="submit" name="cambiar_envio" value="1" class="btn btn-secondary">No, quiero cambiar mis datos</button>
        <a href="index.php?controller=CartController&action=index" class="btn btn-link">Volver al carrito</a>
    </form>
</div>
<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 