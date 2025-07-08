<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>
<div class="container mt-5 main-content">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
            echo htmlspecialchars($_SESSION['success']);
            unset($_SESSION['success']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
            echo htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Productos</h2>
        <a href="index.php?controller=ProductController&action=create" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo Producto
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $prod): ?>
                    <tr>
                        <td><?php echo $prod['id']; ?></td>
                        <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                        <td>$<?php echo number_format($prod['precio'], 2); ?></td>
                        <td><?php echo htmlspecialchars($prod['categoria']); ?></td>
                        <td><?php echo $prod['stock']; ?></td>
                        <td class="text-center">
                            <?php if ($prod['imagen']): ?>
                                <img src="assets/images/<?php echo htmlspecialchars($prod['imagen']); ?>" 
                                     class="img-thumbnail" 
                                     style="max-width: 60px; height: auto;"
                                     alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="index.php?controller=ProductController&action=edit&id=<?php echo $prod['id']; ?>" 
                                   class="btn btn-sm btn-warning" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal<?php echo $prod['id']; ?>"
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <!-- Modal de confirmación para eliminar -->
                            <div class="modal fade" id="deleteModal<?php echo $prod['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $prod['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel<?php echo $prod['id']; ?>">
                                                Confirmar eliminación
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro de que deseas eliminar el producto <strong><?php echo htmlspecialchars($prod['nombre']); ?></strong>?</p>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Si el producto está asociado a pedidos existentes, no podrá ser eliminado.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times"></i> Cancelar
                                            </button>
                                            <a href="index.php?controller=ProductController&action=delete&id=<?php echo $prod['id']; ?>" 
                                               class="btn btn-danger">
                                                <i class="fas fa-trash"></i> Sí, eliminar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html> 