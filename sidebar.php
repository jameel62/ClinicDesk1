<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="info"><a href="#" class="d-block"><?php echo sanitize(Auth::currentUser()['name']); ?> (<?php echo strtoupper(Auth::role()); ?>)</a></div>
    </div>
  </div>
</aside>