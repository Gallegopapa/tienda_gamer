<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>
<div class="container mt-5 main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Productos de la categoría: <?php echo htmlspecialchars($cat['nombre']); ?></h2>
        <?php if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin'): ?>
            <a href="index.php?controller=ProductController&action=create&category_id=<?php echo $cat['id']; ?>" class="btn btn-success">+ Añadir Producto</a>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php if ($productos): foreach ($productos as $prod): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if (!empty($prod['imagen'])): ?>
                        <img src="assets/images/<?php echo htmlspecialchars($prod['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($prod['nombre']); ?></h5>
                        <p class="card-text">$<?php echo number_format($prod['precio'], 2); ?></p>
                        <p class="card-text">Stock: <?php echo $prod['stock']; ?></p>
                        <a href="index.php?controller=ProductController&action=detail&id=<?php echo $prod['id']; ?>" class="btn btn-primary">Ver detalle</a>
                    </div>
                </div>
            </div>
        <?php endforeach; else: ?>
            <p class="text-center">No hay productos en esta categoría.</p>
        <?php endif; ?>
    </div>
    <a href="index.php?controller=CategoryController&action=list" class="btn btn-secondary mt-3">Volver a categorías</a>
</div>
<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 