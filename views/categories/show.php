<div class="container mt-5 main-content">
    <h2 class="mb-4">Productos en la categoría: <?php echo htmlspecialchars($cat['nombre']); ?></h2>
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
                        <a href="index.php?controller=ProductController&action=detail&id=<?php echo $prod['id']; ?>" class="btn btn-primary">Ver detalle</a>
                    </div>
                </div>
            </div>
        <?php endforeach; else: ?>
            <p class="text-center">No hay productos en esta categoría.</p>
        <?php endif; ?>
    </div>
    <a href="index.php?controller=CategoryController&action=list" class="btn btn-secondary">Volver a categorías</a>
</div> 