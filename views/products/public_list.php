<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>
<div class="container mt-5 main-content">
    <h2 class="mb-4 text-center">Nuestras Categorías</h2>
    <div class="row">
        <?php
        $categoryModel = new Category();
        $categorias = $categoryModel->getAll();
        
        foreach ($categorias as $cat): ?>
            <div class="col-md-4 mb-4">
                <a href="index.php?controller=ProductController&action=byCategory&id=<?php echo $cat['id']; ?>" 
                   class="text-decoration-none">
                    <div class="card h-100 shadow category-card">
                        <?php if (!empty($cat['imagen'])): ?>
                            <img src="assets/images/<?php echo htmlspecialchars($cat['imagen']); ?>" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: contain; padding: 15px;"
                                 alt="<?php echo htmlspecialchars($cat['nombre']); ?>">
                        <?php endif; ?>
                        <div class="card-body text-center">
                            <h3 class="card-title h5"><?php echo htmlspecialchars($cat['nombre']); ?></h3>
                            <p class="card-text text-muted">Click para ver productos</p>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.category-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.category-card .card-body {
    background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
}

.category-card .card-title {
    color: #333;
    margin-bottom: 0.5rem;
}

.category-card .card-text {
    font-size: 0.9rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.category-card:hover .card-text {
    opacity: 1;
}
</style>

<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 