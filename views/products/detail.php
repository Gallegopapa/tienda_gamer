<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>
<div class="container mt-5 main-content" style="max-width: 700px;">
    <div class="card mb-4">
        <div class="row g-0">
            <?php if (!empty($prod['imagen'])): ?>
            <div class="col-md-5">
                <img src="assets/images/<?php echo htmlspecialchars($prod['imagen']); ?>" class="img-fluid rounded-start" alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
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
    <a href="javascript:history.back()" class="btn btn-secondary">Volver</a>
</div>
<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 