<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>
<div class="container mt-5 main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Categorías</h2>
        <a href="index.php?controller=CategoryController&action=create" class="btn btn-success">+ Nueva Categoría</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $cat): ?>
                <tr>
                    <td><?php echo $cat['id']; ?></td>
                    <td><?php echo htmlspecialchars($cat['nombre']); ?></td>
                    <td><?php if (!empty($cat['imagen'])): ?><img src="assets/images/<?php echo htmlspecialchars($cat['imagen']); ?>" width="60"><?php endif; ?></td>
                    <td>
                        <a href="index.php?controller=CategoryController&action=edit&id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="index.php?controller=CategoryController&action=delete&id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar esta categoría?');">Eliminar</a>
                        <a href="index.php?controller=ProductController&action=byCategory&id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-primary">Ver productos</a>
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