<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>

<div class="container mt-5 main-content">
    <h2>Mis Pedidos</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php 
            echo htmlspecialchars($_SESSION['success']);
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            echo htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (empty($pedidos)): ?>
        <div class="alert alert-info">
            No tienes pedidos realizados.
        </div>
    <?php else: ?>
        <?php foreach ($pedidos as $pedido): ?>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Pedido #<?php echo htmlspecialchars($pedido['id']); ?></h5>
                        <small class="text-muted">
                            <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($pedido['fecha']))); ?>
                        </small>
                    </div>
                    <span class="badge <?php 
                        switch($pedido['estado']) {
                            case 'pendiente':
                                echo 'bg-warning';
                                break;
                            case 'enviado':
                                echo 'bg-info';
                                break;
                            case 'entregado':
                                echo 'bg-success';
                                break;
                            case 'cancelado':
                                echo 'bg-danger';
                                break;
                            default:
                                echo 'bg-secondary';
                        }
                    ?>">
                        <?php echo ucfirst(htmlspecialchars($pedido['estado'])); ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Información de envío</h6>
                            <p class="mb-1"><strong>Dirección:</strong> <?php echo htmlspecialchars($pedido['direccion']); ?></p>
                            <p class="mb-1"><strong>Ciudad:</strong> <?php echo htmlspecialchars($pedido['ciudad']); ?></p>
                            <p class="mb-1"><strong>País:</strong> <?php echo htmlspecialchars($pedido['pais']); ?></p>
                            <p class="mb-1"><strong>Código Postal:</strong> <?php echo htmlspecialchars($pedido['codigo_postal']); ?></p>
                            <p class="mb-1"><strong>Contacto:</strong> <?php echo htmlspecialchars($pedido['contacto']); ?></p>
                            <p class="mb-1"><strong>Método de pago:</strong> <?php echo htmlspecialchars($pedido['metodo_pago']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Productos</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $total_pedido = 0;
                                        foreach ($pedido['productos'] as $producto): 
                                            $subtotal = $producto['cantidad'] * $producto['precio_unitario'];
                                            $total_pedido += $subtotal;
                                        ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if ($producto['imagen']): ?>
                                                            <img src="assets/images/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                                                                 alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                                                                 class="me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                                        <?php endif; ?>
                                                        <?php echo htmlspecialchars($producto['nombre']); ?>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                                                <td>$<?php echo number_format($producto['precio_unitario'], 2); ?></td>
                                                <td>$<?php echo number_format($subtotal, 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total del pedido:</strong></td>
                                            <td><strong>$<?php echo number_format($total_pedido, 2); ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <?php if ($pedido['estado'] === 'pendiente'): ?>
                        <form method="post" action="index.php?controller=OrderController&action=cancel" class="d-inline">
                            <input type="hidden" name="order_id" value="<?php echo $pedido['id']; ?>">
                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('¿Estás seguro de que deseas cancelar este pedido?')">
                                Cancelar pedido
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $pedido['id']; ?>">
                        Eliminar pedido
                    </button>

                    <!-- Modal de confirmación para eliminar -->
                    <div class="modal fade" id="deleteModal<?php echo $pedido['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $pedido['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel<?php echo $pedido['id']; ?>">Confirmar eliminación</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Estás seguro de que deseas eliminar el pedido #<?php echo htmlspecialchars($pedido['id']); ?>?
                                    <?php if ($pedido['estado'] === 'pendiente'): ?>
                                        <div class="alert alert-warning mt-2">
                                            Este pedido está pendiente. Al eliminarlo, se restaurará el stock de los productos.
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <form method="post" action="index.php?controller=OrderController&action=delete" class="d-inline">
                                        <input type="hidden" name="order_id" value="<?php echo $pedido['id']; ?>">
                                        <button type="submit" class="btn btn-danger">Sí, eliminar pedido</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
