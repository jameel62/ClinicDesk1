<?php require_once __DIR__ . '/../partials/header.php'; require_once __DIR__ . '/../partials/navbar.php'; require_once __DIR__ . '/../partials/sidebar.php'; ?>
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <h1>Doctor Dashboard</h1>
      <?php require_once __DIR__ . '/../partials/alerts.php'; ?>
      <table class="table table-bordered">
        <thead><tr><th>ID</th><th>Time</th><th>Reason</th><th>Action</th></tr></thead>
        <tbody>
          <?php foreach($stats['today_appts'] as $appt): ?>
          <tr>
            <td><?php echo $appt['id']; ?></td>
            <td><?php echo sanitize($appt['appt_time']); ?></td>
            <td><?php echo sanitize($appt['reason']); ?></td>
            <td>
              <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal<?php echo $appt['id']; ?>">Prescribe</button>
              <div class="modal fade" id="modal<?php echo $appt['id']; ?>">
                <div class="modal-dialog">
                  <form action="index.php?page=prescription&action=add" method="POST" enctype="multipart/form-data" class="modal-content">
                    <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                    <input type="hidden" name="appointment_id" value="<?php echo $appt['id']; ?>">
                    <div class="modal-body">
                      <div class="form-group"><label>Diagnosis</label><textarea name="diagnosis" class="form-control" required></textarea></div>
                      <div class="form-group"><label>Medications</label><textarea name="medications" class="form-control" required></textarea></div>
                      <div class="form-group"><label>Notes</label><textarea name="notes" class="form-control"></textarea></div>
                      <div class="form-group"><label>PDF Attachment</label><input type="file" name="prescription_file"></div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></div>
                  </form>
                </div>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>