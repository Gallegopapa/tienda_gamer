<!DOCTYPE html>
<html lang="es" class="h-100">
<?php include 'views/layouts/head.php'; ?>
<body class="d-flex flex-column h-100">
<?php include 'views/layouts/header.php'; ?>

<main class="flex-shrink-0">
    <div class="container mt-5">
        <h2 class="mb-4">Categorías de Productos</h2>

        <?php if (empty($categorias)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No hay categorías disponibles.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($categorias as $cat): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 category-card">
                            <?php if (!empty($cat['imagen'])): ?>
                                <img src="<?php echo BASE_URL; ?>assets/images/<?php echo htmlspecialchars($cat['imagen']); ?>" 
                                     class="card-img-top" 
                                     alt="<?php echo htmlspecialchars($cat['nombre']); ?>"
                                     style="height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($cat['nombre']); ?></h5>
                                <a href="<?php echo rtrim(BASE_URL, '/'); ?>/productos/categoria/<?php echo $cat['id']; ?>" 
                                   class="btn btn-primary mt-2">
                                    <i class="fas fa-eye"></i> Ver productos
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<footer class="footer mt-auto py-3 bg-dark">
    <div class="container">
        <span class="text-white">&copy; <?php echo date('Y'); ?> Tienda Gamer. Todos los derechos reservados.</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 