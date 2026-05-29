<?php require_once __DIR__ . '/../partials/header.php'; require_once __DIR__ . '/../partials/navbar.php'; require_once __DIR__ . '/../partials/sidebar.php'; ?>
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <h1>Patient Dashboard</h1>
      <table class="table table-bordered">
        <thead><tr><th>ID</th><th>Date</th><th>Status</th><th>Prescription</th></tr></thead>
        <tbody>
          <?php foreach($stats['my_appts'] as $appt): ?>
          <tr>
            <td><?php echo $appt['id']; ?></td>
            <td><?php echo $appt['appt_date']; ?></td>
            <td><?php echo $appt['status']; ?></td>
            <td>
              <?php if($appt['status'] === 'completed'): ?>
                <a href="index.php?page=prescription&action=download&id=<?php echo $appt['id']; ?>" class="btn btn-sm btn-info">Download PDF</a>
              <?php else: ?> - <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>