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
        <h2 class="mb-0">Categorías</h2>
        <a href="index.php?controller=CategoryController&action=create" class="btn btn-success">
            <i class="fas fa-plus"></i> Nueva Categoría
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $cat): ?>
                    <tr>
                        <td><?php echo $cat['id']; ?></td>
                        <td><?php echo htmlspecialchars($cat['nombre']); ?></td>
                        <td class="text-center">
                            <?php if (!empty($cat['imagen'])): ?>
                                <img src="assets/images/<?php echo htmlspecialchars($cat['imagen']); ?>" 
                                     class="img-thumbnail" 
                                     style="max-width: 60px; height: auto;"
                                     alt="<?php echo htmlspecialchars($cat['nombre']); ?>">
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="index.php?controller=CategoryController&action=edit&id=<?php echo $cat['id']; ?>" 
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal<?php echo $cat['id']; ?>">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                                <a href="index.php?controller=ProductController&action=byCategory&id=<?php echo $cat['id']; ?>" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-box"></i> Ver productos
                                </a>
                            </div>

                            <!-- Modal de confirmación para eliminar -->
                            <div class="modal fade" id="deleteModal<?php echo $cat['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $cat['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel<?php echo $cat['id']; ?>">
                                                Confirmar eliminación
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro de que deseas eliminar la categoría <strong><?php echo htmlspecialchars($cat['nombre']); ?></strong>?</p>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Si la categoría tiene productos asociados, no podrá ser eliminada.
                                                Deberás eliminar o reasignar los productos primero.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times"></i> Cancelar
                                            </button>
                                            <a href="index.php?controller=CategoryController&action=delete&id=<?php echo $cat['id']; ?>" 
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