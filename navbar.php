<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <form action="<?php echo BASE_URL; ?>index.php?page=logout" method="POST" style="display:inline;">
        <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
        <button type="submit" class="btn btn-danger btn-sm">Logout</button>
      </form>
    </li>
  </ul>
</nav>