<!DOCTYPE html>
<html lang="es">
<?php include 'views/layouts/head.php'; ?>
<body>
<?php include 'views/layouts/header.php'; ?>
<div class="container mt-5 main-content" style="max-width: 600px;">
    <h2 class="mb-4"><?php echo isset($prod) ? 'Editar Producto' : 'Nuevo Producto'; ?></h2>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data" action="<?php echo isset($prod) ? 'index.php?controller=ProductController&action=edit&id=' . $prod['id'] : 'index.php?controller=ProductController&action=create'; ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required value="<?php echo isset($prod) ? htmlspecialchars($prod['nombre']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" min="0" class="form-control" id="precio" name="precio" required value="<?php echo isset($prod) ? $prod['precio'] : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Categoría</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <option value="">Selecciona una categoría</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php if ((isset($prod) && $prod['category_id'] == $cat['id']) || (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id'])) echo 'selected'; ?>><?php echo htmlspecialchars($cat['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo isset($prod) ? htmlspecialchars($prod['descripcion']) : ''; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen</label>
            <input type="file" class="form-control" id="imagen" name="imagen" <?php echo isset($prod) ? '' : 'required'; ?>>
            <?php if (isset($prod) && $prod['imagen']): ?>
                <img src="assets/images/<?php echo htmlspecialchars($prod['imagen']); ?>" width="100" class="mt-2">
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" min="1" max="10" required value="<?php echo isset($prod) ? $prod['stock'] : '1'; ?>">
        </div>
        <button type="submit" class="btn btn-primary"><?php echo isset($prod) ? 'Actualizar' : 'Crear'; ?></button>
        <a href="index.php?controller=ProductController&action=adminList" class="btn btn-secondary">Volver</a>
    </form>
</div>
<?php include 'views/layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 