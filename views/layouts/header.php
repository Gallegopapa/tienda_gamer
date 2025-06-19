<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Tienda Gamer</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?controller=ProductController&action=publicList">Productos</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?controller=CartController&action=index">Carrito</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <?php if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin'): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Administración
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                <li><a class="dropdown-item" href="index.php?controller=CategoryController&action=list">Editar categorías</a></li>
                <li><a class="dropdown-item" href="index.php?controller=ProductController&action=adminList">Editar productos</a></li>
                <li><a class="dropdown-item" href="index.php?controller=OrderController&action=adminList">Pedidos</a></li>
              </ul>
            </li>
          <?php endif; ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php echo htmlspecialchars($_SESSION['user_nombre']); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="index.php?controller=OrderController&action=list">Mis pedidos</a></li>
              <li><a class="dropdown-item" href="index.php?controller=UserController&action=account">Configurar cuenta</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="index.php?controller=AuthController&action=logout">Cerrar sesión</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="index.php?controller=AuthController&action=login">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav> 