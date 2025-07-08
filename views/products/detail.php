<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>
<div class="container mt-5 main-content">
    <div class="card mb-4">
        <div class="row g-0">
            <?php if (!empty($prod['imagen'])): ?>
            <div class="col-md-5">
                <img src="assets/images/<?php echo htmlspecialchars($prod['imagen']); ?>" 
                     class="img-fluid rounded-start" 
                     style="object-fit: contain; max-height: 400px; width: 100%; padding: 20px;"
                     alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
            </div>
            <?php endif; ?>
            <div class="col-md-7">
                <div class="card-body">
                    <h3 class="card-title"><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                    <h4 class="text-success">$<?php echo number_format($prod['precio'], 2); ?></h4>
                    <p class="card-text">Stock disponible: <?php echo $prod['stock']; ?></p>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($prod['descripcion'])); ?></p>
                    <form method="post" action="index.php?controller=CartController&action=add&id=<?php echo $prod['id']; ?>">
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" max="<?php echo $prod['stock']; ?>" value="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary" <?php if ($prod['stock'] < 1) echo 'disabled'; ?>>Agregar al carrito</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Sugerencias -->
    <div class="mt-5">
        <h3 class="mb-4">También te puede interesar</h3>
        <?php if (!empty($sugerencias)): ?>
            <div class="row">
                <?php foreach ($sugerencias as $sugerencia): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 producto-sugerido">
                            <?php if (!empty($sugerencia['imagen'])): ?>
                                <img src="assets/images/<?php echo htmlspecialchars($sugerencia['imagen']); ?>" 
                                     class="card-img-top" 
                                     style="height: 200px; object-fit: contain; padding: 15px;"
                                     alt="<?php echo htmlspecialchars($sugerencia['nombre']); ?>">
                            <?php endif; ?>
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($sugerencia['nombre']); ?></h5>
                                <p class="card-text">$<?php echo number_format($sugerencia['precio'], 2); ?></p>
                                <a href="index.php?controller=ProductController&action=detail&id=<?php echo $sugerencia['id']; ?>" 
                                   class="btn btn-outline-primary">Ver detalle</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <p class="mb-0">¡Más productos próximamente!</p>
                <small class="text-muted">Estamos trabajando para traerte más productos increíbles.</small>
            </div>
        <?php endif; ?>
    </div>

    <a href="javascript:history.back()" class="btn btn-secondary mt-3">Volver</a>
</div>

<style>
.producto-sugerido {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.producto-sugerido:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>

<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 