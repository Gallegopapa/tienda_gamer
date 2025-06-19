<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>
<div class="container mt-5 main-content">
    <h2 class="mb-4">Carrito de Compras</h2>
    <?php if (empty($carrito)): ?>
        <div class="alert alert-info">Tu carrito está vacío.</div>
    <?php else: ?>
        <form method="post" action="index.php?controller=CartController&action=update">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th style="width: 180px;">Cantidad</th>
                    <th>Subtotal</th>
                    <th style="width: 120px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; foreach ($carrito as $item): $subtotal = $item['precio'] * $item['cantidad']; $total += $subtotal; ?>
                <tr>
                    <td><?php if ($item['imagen']): ?><img src="assets/images/<?php echo htmlspecialchars($item['imagen']); ?>" width="60"><?php endif; ?></td>
                    <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                    <td>$<?php echo number_format($item['precio'], 2); ?></td>
                    <td>
                        <form method="post" action="index.php?controller=CartController&action=update" class="d-flex flex-nowrap align-items-center gap-2">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <input type="number" name="cantidad" min="1" max="<?php echo $item['stock']; ?>" value="<?php echo $item['cantidad']; ?>" class="form-control form-control-sm" style="width:70px;">
                            <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                    <td>
                        <a href="index.php?controller=CartController&action=remove&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger w-100 mb-1" onclick="return confirm('¿Eliminar este producto del carrito?');">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </form>
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h4 class="mb-0">Total: $<?php echo number_format($total, 2); ?></h4>
            <a href="index.php?controller=CartController&action=clear" class="btn btn-warning">Vaciar carrito</a>
        </div>
        <div class="mt-4 d-flex flex-wrap gap-2">
            <a href="index.php" class="btn btn-secondary">Seguir comprando</a>
            <a href="index.php?controller=OrderController&action=checkout" class="btn btn-success">Finalizar compra</a>
        </div>
    <?php endif; ?>
</div>
<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 