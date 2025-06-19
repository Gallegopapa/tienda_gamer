<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>
<div class="container mt-5 main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Productos</h2>
        <a href="index.php?controller=ProductController&action=create" class="btn btn-success">+ Nuevo Producto</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $prod): ?>
                <tr>
                    <td><?php echo $prod['id']; ?></td>
                    <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                    <td>$<?php echo number_format($prod['precio'], 2); ?></td>
                    <td><?php echo htmlspecialchars($prod['categoria']); ?></td>
                    <td><?php echo $prod['stock']; ?></td>
                    <td><?php if ($prod['imagen']): ?><img src="assets/images/<?php echo htmlspecialchars($prod['imagen']); ?>" width="60"><?php endif; ?></td>
                    <td>
                        <a href="index.php?controller=ProductController&action=edit&id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="index.php?controller=ProductController&action=delete&id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este producto?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 