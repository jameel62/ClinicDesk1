<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/assets/adminlte/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card">
    <div class="card-body login-card-body">
      <?php require_once __DIR__ . '/../partials/alerts.php'; ?>
      <form action="index.php?page=login" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
        <div class="form-group"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
        <div class="form-group"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>