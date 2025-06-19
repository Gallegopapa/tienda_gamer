<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>
<div class="container mt-5 main-content">
    <h2 class="mb-4">Mis pedidos</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">¡Pedido realizado con éxito!</div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (empty($pedidos)): ?>
        <div class="alert alert-info">No tienes pedidos realizados.</div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Dirección</th>
                    <th>País</th>
                    <th>Ciudad</th>
                    <th>Código Postal</th>
                    <th>Contacto</th>
                    <th>Método de pago</th>
                    <th>Estado</th>
                    <th>Acciones</th> <!-- Nueva columna -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                <tr>
                    <td><?php echo $pedido['id']; ?></td>
                    <td><?php echo $pedido['fecha']; ?></td>
                    <td><?php echo htmlspecialchars($pedido['direccion']); ?></td>
                    <td><?php echo htmlspecialchars($pedido['pais']); ?></td>
                    <td><?php echo htmlspecialchars($pedido['ciudad']); ?></td>
                    <td><?php echo htmlspecialchars($pedido['codigo_postal']); ?></td>
                    <td><?php echo htmlspecialchars($pedido['contacto']); ?></td>
                    <td><?php echo htmlspecialchars($pedido['metodo_pago']); ?></td>
                    <td><?php echo ucfirst($pedido['estado']); ?></td>
                    <td>
                        <?php if ($pedido['estado'] === 'pendiente'): ?>
                            <form method="POST" action="index.php?controller=OrderController&action=cancel" onsubmit="return confirm('¿Estás seguro de cancelar este pedido?');">
                                <input type="hidden" name="order_id" value="<?php echo $pedido['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted">No disponible</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="index.php" class="btn btn-secondary mt-3">Volver al inicio</a>
</div>

<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
