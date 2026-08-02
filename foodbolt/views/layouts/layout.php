<?php /** @var string $content, array $config, int $cartCount */ 
$config ??= [
    'name' => 'FoodBolt',
    'tagline' => '',
];
$cartCount ??= 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($config['name']) ?> — <?= e($config['tagline']) ?></title>
  <link rel="stylesheet" href="<?= e(url('assets/style.css')) ?>">
</head>
<body>
  <header class="site-header">
    <div class="header-inner">
      <a href="<?= e(url('')) ?>" class="brand">
        <span class="logo">🍽️</span>
        <span><?= e($config['name']) ?><small><?= e($config['tagline']) ?></small></span>
      </a>
      <nav class="header-nav">
        <a href="<?= e(url('admin/login')) ?>" class="btn btn-ghost btn-sm">Admin</a>
        <a href="<?= e(url('cart/index')) ?>" class="btn btn-outline cart-badge">
          🛒 Cart
          <?php if (!empty($cartCount) && $cartCount > 0): ?><span class="count"><?= (int) $cartCount ?></span><?php endif; ?>
        </a>
      </nav>
    </div>
  </header>

  <?php $flash = flash_get(); ?>
  <?php if ($flash): ?>
    <div class="flash">
      <?php foreach ($flash as $f): ?>
        <div class="flash-msg flash-<?= e($f['type']) ?>"><?= e($f['message']) ?></div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <main>
    <?= $content ?>
  </main>

  <footer class="site-footer">
    <p><?= e($config['name']) ?> &middot; <?= e($config['tagline']) ?> &middot; <a href="<?= e(url('admin/login')) ?>">Admin panel</a></p>
  </footer>

  <script src="<?= e(url('assets/app.js')) ?>"></script>
</body>
</html>
