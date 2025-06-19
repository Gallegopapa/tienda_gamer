<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>
<div class="container mt-4 main-content">
    <h1 class="text-center">Bienvenido a Tienda Gamer</h1>
    <p class="text-center">¡Encuentra los mejores productos para gamers!</p>
    <div class="row mt-5">
        <?php
        require_once 'models/Product.php';
        $productModel = new Product();
        $productos = $productModel->getRecientes(6);
        if ($productos):
            foreach ($productos as $prod): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($prod['imagen'])): ?>
                            <img src="assets/images/<?php echo htmlspecialchars($prod['imagen']); ?>" 
                                 class="card-img-top" 
                                 style="height: 150px; object-fit: contain; padding: 10px;"
                                 alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($prod['nombre']); ?></h5>
                            <p class="card-text">$<?php echo number_format($prod['precio'], 2); ?></p>
                            <a href="index.php?controller=ProductController&action=detail&id=<?php echo $prod['id']; ?>" class="btn btn-primary">Ver detalle</a>
                        </div>
                    </div>
                </div>
            <?php endforeach;
        else: ?>
            <p class="text-center">No hay productos para mostrar.</p>
        <?php endif; ?>
    </div>
</div>
<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 