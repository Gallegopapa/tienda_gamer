<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>
<div class="container-fluid mt-4 main-content">
    <h2 class="mb-4">Administración de Pedidos</h2>
    <?php if (empty($pedidos)): ?>
        <div class="alert alert-info">No hay pedidos registrados.</div>
    <?php else: ?>
        <div class="row flex-nowrap overflow-auto pb-3">
            <?php foreach ($pedidos as $pedido): ?>
                <div class="col-md-4 col-lg-3 mb-3" style="min-width: 350px;">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Pedido #<?php echo $pedido['id']; ?></h6>
                                <span class="badge bg-light text-dark"><?php echo ucfirst($pedido['estado']); ?></span>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="mb-3">
                                <h6 class="fw-bold mb-2">Cliente</h6>
                                <p class="mb-1 small"><strong>Nombre:</strong> <?php echo htmlspecialchars($pedido['user_nombre']); ?></p>
                                <p class="mb-1 small"><strong>Email:</strong> <?php echo htmlspecialchars($pedido['user_email']); ?></p>
                                <p class="mb-1 small"><strong>Contacto:</strong> <?php echo htmlspecialchars($pedido['contacto']); ?></p>
                            </div>

                            <div class="mb-3">
                                <h6 class="fw-bold mb-2">Envío</h6>
                                <p class="mb-1 small"><strong>Dirección:</strong> <?php echo htmlspecialchars($pedido['direccion']); ?></p>
                                <p class="mb-1 small"><strong>País:</strong> <?php echo htmlspecialchars($pedido['pais']); ?></p>
                                <p class="mb-1 small"><strong>Ciudad:</strong> <?php echo htmlspecialchars($pedido['ciudad']); ?></p>
                                <p class="mb-1 small"><strong>CP:</strong> <?php echo htmlspecialchars($pedido['codigo_postal']); ?></p>
                            </div>

                            <div class="mb-3">
                                <h6 class="fw-bold mb-2">Productos</h6>
                                <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Producto</th>
                                                <th>Cant.</th>
                                                <th>Precio</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $total = 0;
                                            foreach ($pedido['productos'] as $item): 
                                                $subtotal = $item['precio'] * $item['cantidad'];
                                                $total += $subtotal;
                                            ?>
                                            <tr>
                                                <td class="small"><?php echo htmlspecialchars($item['nombre']); ?></td>
                                                <td class="small"><?php echo $item['cantidad']; ?></td>
                                                <td class="small">$<?php echo number_format($subtotal, 2); ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" class="text-end small"><strong>Total:</strong></td>
                                                <td class="small"><strong>$<?php echo number_format($total, 2); ?></strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="mb-2">
                                <p class="mb-1 small"><strong>Fecha:</strong> <?php echo $pedido['fecha']; ?></p>
                                <p class="mb-1 small"><strong>Pago:</strong> <?php echo htmlspecialchars($pedido['metodo_pago']); ?></p>
                            </div>

                            <div class="mt-3">
                                <form method="post" action="index.php?controller=OrderController&action=updateStatus" class="d-inline">
                                    <input type="hidden" name="order_id" value="<?php echo $pedido['id']; ?>">
                                    <select name="estado" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                        <option value="pendiente" <?php echo $pedido['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                        <option value="enviado" <?php echo $pedido['estado'] == 'enviado' ? 'selected' : ''; ?>>Enviado</option>
                                        <option value="entregado" <?php echo $pedido['estado'] == 'entregado' ? 'selected' : ''; ?>>Entregado</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.overflow-auto {
    scrollbar-width: thin;
    scrollbar-color: #6c757d #f8f9fa;
}

.overflow-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-auto::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 4px;
}

.overflow-auto::-webkit-scrollbar-thumb {
    background-color: #6c757d;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar {
    width: 6px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f8f9fa;
}

.table-responsive::-webkit-scrollbar-thumb {
    background-color: #6c757d;
    border-radius: 3px;
}
</style>

<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 