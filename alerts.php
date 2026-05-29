<?php if ($msg = flash('success')): ?>
    <div class="alert alert-success"><?php echo $msg; ?></div>
<?php endif; ?>
<?php if ($msg = flash('error')): ?>
    <div class="alert alert-danger"><?php echo $msg; ?></div>
<?php endif; ?>