<?php /** @var array $kitchen, array $byCategory, array $config, int $cartCount */ ?>
<div class="kitchen-hero">
  <img src="<?= e($kitchen['image']) ?>" alt="<?= e($kitchen['name']) ?>">
  <div class="info">
    <span class="cuisine"><?= e($kitchen['cuisine']) ?></span>
    <h1><?= e($kitchen['name']) ?></h1>
    <p class="desc"><?= e($kitchen['description']) ?></p>
    <a href="<?= e(url('')) ?>" class="btn btn-outline btn-sm">← All kitchens</a>
  </div>
</div>

<?php if (!$byCategory): ?>
  <div class="empty"><div class="icon">🍽️</div><p>No menu items yet.</p></div>
<?php else: ?>
  <?php foreach ($byCategory as $category => $items): ?>
    <div class="category-block">
      <h3><?= e($category) ?></h3>
      <div class="grid grid-menu">
        <?php foreach ($items as $item): ?>
          <div class="card menu-item <?= empty($item['available']) ? 'unavailable' : '' ?>">
            <div class="card-img">
              <img src="<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>" loading="lazy">
              <span class="category-tag"><?= e($item['category']) ?></span>
            </div>
            <div class="card-body">
              <h3><?= e($item['name']) ?></h3>
              <p><?= e($item['description']) ?></p>
            </div>
            <div class="card-footer">
              <span class="price"><?= money((float) $item['price'], $config['currency']) ?></span>
              <?php if (!empty($item['available'])): ?>
                <form action="<?= e(url('cart/add')) ?>" method="post">
                  <?= csrf_field() ?>
                  <input type="hidden" name="item_id" value="<?= e($item['id']) ?>">
                  <input type="hidden" name="kitchen_id" value="<?= e($kitchen['id']) ?>">
                  <button type="submit" class="btn btn-primary btn-sm">+ Add</button>
                </form>
              <?php else: ?>
                <span class="badge badge-cancelled">Unavailable</span>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
