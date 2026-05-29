<?php require_once __DIR__ . '/../partials/header.php'; require_once __DIR__ . '/../partials/navbar.php'; require_once __DIR__ . '/../partials/sidebar.php'; ?>
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <h1>Admin Dashboard</h1>
      <p>Patients: <?php echo $stats['total_patients']; ?></p>
      <p>Doctors: <?php echo $stats['total_doctors']; ?></p>
      <p>Today Appointments: <?php echo $stats['appts_today']; ?></p>
    </div>
  </section>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>