<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>
<div class="container mt-5 main-content">
    <h2 class="mb-4">Explora por Categoría</h2>
    <!-- Slider de categorías -->
    <div id="categoriasCarousel" class="carousel slide mb-5" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            <?php
            $categoryModel = new Category();
            $categorias = $categoryModel->getAll();
            $categoriasPorSlide = 3; // Número de categorías por slide
            $totalSlides = ceil(count($categorias) / $categoriasPorSlide);
            
            for ($i = 0; $i < $totalSlides; $i++): ?>
                <div class="carousel-item<?php if ($i === 0) echo ' active'; ?>">
                    <div class="row justify-content-center">
                        <?php
                        $start = $i * $categoriasPorSlide;
                        $end = min($start + $categoriasPorSlide, count($categorias));
                        for ($j = $start; $j < $end; $j++):
                            $cat = $categorias[$j];
                        ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 shadow-sm">
                                    <?php if (!empty($cat['imagen'])): ?>
                                        <img src="assets/images/<?php echo htmlspecialchars($cat['imagen']); ?>" 
                                             class="card-img-top" 
                                             style="height: 150px; object-fit: contain; padding: 10px;"
                                             alt="<?php echo htmlspecialchars($cat['nombre']); ?>">
                                    <?php endif; ?>
                                    <div class="card-body text-center">
                                        <h5 class="card-title"><?php echo htmlspecialchars($cat['nombre']); ?></h5>
                                        <a href="index.php?controller=ProductController&action=byCategory&id=<?php echo $cat['id']; ?>" 
                                           class="btn btn-primary">Ver productos</a>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
        <?php if ($totalSlides > 1): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#categoriasCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#categoriasCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
            <div class="carousel-indicators position-relative mt-3">
                <?php for ($i = 0; $i < $totalSlides; $i++): ?>
                    <button type="button" 
                            data-bs-target="#categoriasCarousel" 
                            data-bs-slide-to="<?php echo $i; ?>" 
                            <?php if ($i === 0) echo 'class="active"'; ?>
                            aria-label="Slide <?php echo $i + 1; ?>">
                    </button>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>

    <style>
    .carousel-control-prev,
    .carousel-control-next {
        width: 40px;
        height: 40px;
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    .carousel-control-prev {
        left: -20px;
    }

    .carousel-control-next {
        right: -20px;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        background-color: rgba(0, 0, 0, 0.8);
        opacity: 1;
    }

    .carousel-indicators {
        display: none;
    }

    #categoriasCarousel {
        padding: 0 40px;
    }
    </style>

    <h2 class="mb-4">Productos destacados</h2>
    <div class="row">
        <?php
        $productModel = new Product();
        $productos = $productModel->getRecientes(20); // Traer varios para elegir aleatorios
        shuffle($productos);
        $aleatorios = array_slice($productos, 0, 6);
        foreach ($aleatorios as $prod): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if (!empty($prod['imagen'])): ?>
                        <img src="assets/images/<?php echo htmlspecialchars($prod['imagen']); ?>" 
                             class="card-img-top" 
                             style="height: 150px; object-fit: contain; padding: 10px;"
                             alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($prod['nombre']); ?></h5>
                        <p class="card-text">$<?php echo number_format($prod['precio'], 2); ?></p>
                        <p class="card-text">Stock: <?php echo $prod['stock']; ?></p>
                        <a href="index.php?controller=ProductController&action=detail&id=<?php echo $prod['id']; ?>" class="btn btn-primary">Ver detalle</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 