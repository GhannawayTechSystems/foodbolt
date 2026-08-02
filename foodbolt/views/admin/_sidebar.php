<?php /** @var string $active, array $config */ ?>
<aside class="admin-sidebar">
  <a href="<?= e(url('admin/index')) ?>" class="<?= $active === 'dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
  <a href="<?= e(url('admin/kitchens')) ?>" class="<?= $active === 'kitchens' ? 'active' : '' ?>">🍳 Kitchens</a>
  <a href="<?= e(url('admin/menu')) ?>" class="<?= $active === 'menu' ? 'active' : '' ?>">🍽️ Menu items</a>
  <a href="<?= e(url('admin/orders')) ?>" class="<?= $active === 'orders' ? 'active' : '' ?>">📦 Orders</a>
  <a href="<?= e(url('')) ?>" >🌐 View site</a>
  <a href="<?= e(url('admin/logout')) ?>">🚪 Logout</a>
</aside>
