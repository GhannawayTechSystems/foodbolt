<?php /** @var array $kitchens, array $config, int $cartCount */ ?>
<section class="hero">
  <h1>Order from multiple kitchens, one delivery</h1>
  <p>Browse your favorite restaurants, add dishes to a single cart, and we'll coordinate everything for you.</p>
  <a href="#kitchens" class="btn btn-primary" style="font-size:1rem;padding:14px 28px;">Explore kitchens</a>
</section>

<section id="kitchens">
  <div class="section-title">
    <h2>Our kitchens</h2>
  </div>
  <?php if (!$kitchens): ?>
    <div class="empty"><div class="icon">🍳</div><p>No kitchens yet. Add some from the admin panel.</p></div>
  <?php else: ?>
    <div class="grid grid-kitchens">
      <?php foreach ($kitchens as $k): ?>
        <a href="<?= e(url('kitchen/show?id=' . $k['id'])) ?>" class="card" style="text-decoration:none;">
          <div class="card-img">
            <img src="<?= e($k['image']) ?>" alt="<?= e($k['name']) ?>" loading="lazy">
          </div>
          <div class="card-body">
            <span class="cuisine"><?= e($k['cuisine']) ?></span>
            <h3><?= e($k['name']) ?></h3>
            <p><?= e($k['description']) ?></p>
          </div>
          <div class="card-footer">
            <span class="btn btn-primary btn-sm">View menu →</span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
