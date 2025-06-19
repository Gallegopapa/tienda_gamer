<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>
<div class="container mt-5 main-content" style="max-width: 500px;">
    <h2 class="mb-4"><?php echo isset($cat) ? 'Editar Categoría' : 'Nueva Categoría'; ?></h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data" action="<?php echo isset($cat) ? 'index.php?controller=CategoryController&action=edit&id=' . $cat['id'] : 'index.php?controller=CategoryController&action=create'; ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la categoría</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required value="<?php echo isset($cat) ? htmlspecialchars($cat['nombre']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen de referencia</label>
            <input type="file" class="form-control" id="imagen" name="imagen">
            <?php if (isset($cat) && !empty($cat['imagen'])): ?>
                <img src="assets/images/<?php echo htmlspecialchars($cat['imagen']); ?>" width="100" class="mt-2">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo isset($cat) ? 'Actualizar' : 'Crear'; ?></button>
        <a href="index.php?controller=CategoryController&action=list" class="btn btn-secondary">Volver</a>
    </form>
</div>
<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 