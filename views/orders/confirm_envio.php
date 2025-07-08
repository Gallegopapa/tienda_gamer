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

    <div class="row g-3">
        <!-- Formulario para confirmar pedido -->
        <div class="col-md-8">
            <form method="post" id="confirmarForm">
                <input type="hidden" name="confirmar_envio" value="1">
                <div class="mb-3">
                    <label for="metodo_pago" class="form-label">Método de pago</label>
                    <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                        <option value="">Seleccione un método de pago</option>
                        <option value="Tarjeta">Tarjeta</option>
                        <option value="Transferencia">Transferencia</option>
                        <option value="Contra reembolso">Contra reembolso</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success w-100">Confirmar y finalizar pedido</button>
            </form>
        </div>
        
        <!-- Formulario para cambiar datos -->
        <div class="col-md-4">
            <form method="post" id="cambiarForm">
                <input type="hidden" name="cambiar_envio" value="1">
                <div class="mb-3">
                    <label class="form-label">&nbsp;</label> <!-- Espaciador para alinear con el select -->
                    <button type="submit" class="btn btn-secondary w-100">Cambiar mis datos</button>
                </div>
            </form>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="index.php?controller=CartController&action=index" class="btn btn-link">Volver al carrito</a>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 