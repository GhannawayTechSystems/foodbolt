<?php /** @var array $items, float $subtotal, float $fee, float $total, array $config, int $cartCount */ ?>
<div class="section-title">
  <h2>Your cart</h2>
  <a href="<?= e(url('')) ?>" class="btn btn-ghost btn-sm">← Keep browsing</a>
</div>

<?php if (!$items): ?>
  <div class="empty"><div class="icon">🛒</div><p>Your cart is empty.<br><a href="<?= e(url('')) ?>">Browse kitchens</a> to start an order.</p></div>
<?php else: ?>
  <div class="cart-layout">
    <div class="cart-list">
      <?php foreach ($items as $i): ?>
        <div class="cart-row">
          <img src="<?= e($i['image']) ?>" alt="<?= e($i['name']) ?>">
          <div>
            <div class="name"><?= e($i['name']) ?></div>
            <div class="kitchen">from <?= e($i['kitchen_name']) ?> · <?= money((float) $i['price'], $config['currency']) ?> each</div>
            <div class="price" style="margin-top:4px;"><?= money((float) $i['price'] * $i['qty'], $config['currency']) ?></div>
          </div>
          <div style="display:flex;flex-direction:column;gap:8px;align-items:flex-end;">
            <form action="<?= e(url('cart/update')) ?>" method="post" data-qty class="qty-control">
              <?= csrf_field() ?>
              <input type="hidden" name="item_id" value="<?= e($i['item_id']) ?>">
              <button type="button" data-qty-down>−</button>
              <span><?= (int) $i['qty'] ?></span>
              <input type="hidden" name="qty" value="<?= (int) $i['qty'] ?>">
              <button type="button" data-qty-up>+</button>
            </form>
            <form action="<?= e(url('cart/remove')) ?>" method="post" class="inline-form">
              <?= csrf_field() ?>
              <input type="hidden" name="item_id" value="<?= e($i['item_id']) ?>">
              <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--error);">Remove</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <aside class="summary">
      <h3>Order summary</h3>
      <div style="margin-bottom:14px;">
        <div style="font-size:.82rem;color:var(--neutral-500);margin-bottom:6px;font-weight:600;">DELIVERING FROM</div>
        <?php foreach (array_unique(array_column($items, 'kitchen_name')) as $kn): ?>
          <span class="kitchen-pill"><?= e($kn) ?></span>
        <?php endforeach; ?>
      </div>
      <div class="summary-row"><span>Subtotal</span><span><?= money($subtotal, $config['currency']) ?></span></div>
      <div class="summary-row"><span>Delivery fee</span><span><?= money($fee, $config['currency']) ?></span></div>
      <div class="summary-row total"><span>Total</span><span><?= money($total, $config['currency']) ?></span></div>
      <a href="<?= e(url('cart/checkout')) ?>" class="btn btn-primary btn-block" style="margin-top:18px;">Checkout →</a>
    </aside>
  </div>
<?php endif; ?>
