<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>

<div class="container mt-5 main-content text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h1 class="display-1 text-danger">404</h1>
                    <h2 class="mb-4">Página no encontrada</h2>
                    
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo htmlspecialchars($_SESSION['error_message']);
                            unset($_SESSION['error_message']);
                            ?>
                        </div>
                    <?php else: ?>
                        <p class="lead">Lo sentimos, la página que estás buscando no existe o no tienes permisos para acceder a ella.</p>
                    <?php endif; ?>

                    <div class="mt-4">
                        <a href="index.php" class="btn btn-primary">Volver al inicio</a>
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="index.php?controller=AuthController&action=login" class="btn btn-outline-primary">Iniciar sesión</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 