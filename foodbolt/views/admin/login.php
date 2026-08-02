<?php /** @var array $config, int $cartCount */ ?>
<div class="form-card">
  <h2 style="margin-bottom:6px;">Admin login</h2>
  <p style="color:var(--neutral-500);font-size:.9rem;margin-bottom:24px;">Sign in to manage kitchens, menus, and orders.</p>
  <form method="post">
    <?= csrf_field() ?>
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required autofocus>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Sign in</button>
  </form>
  <p style="margin-top:16px;text-align:center;font-size:.82rem;color:var(--neutral-400);">Default: gts / gts1211 (change in app/config.php)</p>
</div>
