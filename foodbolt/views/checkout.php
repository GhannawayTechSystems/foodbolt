<?php /** @var array $items, float $subtotal, float $fee, float $total, array $config, int $cartCount */ ?>
<div class="section-title">
  <h2>Checkout</h2>
  <a href="<?= e(url('cart/index')) ?>" class="btn btn-ghost btn-sm">← Back to cart</a>
</div>

<div class="cart-layout">
  <form action="<?= e(url('order/place')) ?>" method="post" class="form-card" style="max-width:none;">
    <?= csrf_field() ?>
    <div class="form-row">
      <div class="form-group">
        <label for="name">Full name</label>
        <input type="text" id="name" name="customer_name" required placeholder="Jane Doe">
      </div>
      <div class="form-group">
        <label for="phone">Phone number</label>
        <input type="tel" id="phone" name="customer_phone" required placeholder="+1 555 123 4567">
      </div>
    </div>
    <div class="form-group">
      <label for="address">Delivery address</label>
      <textarea id="address" name="customer_address" required placeholder="Street, building, apartment, city"></textarea>
    </div>
    <div class="form-group">
      <label for="notes">Order notes (optional)</label>
      <textarea id="notes" name="notes" placeholder="Allergies, doorbell instructions, etc."></textarea>
    </div>
    <button type="submit" class="btn btn-primary btn-block" style="font-size:1rem;padding:14px;">Place order — <?= money($total, $config['currency']) ?></button>
  </form>

  <aside class="summary">
    <h3>Your items</h3>
    <?php foreach ($items as $i): ?>
      <div class="summary-row">
        <span><?= (int) $i['qty'] ?>× <?= e($i['name']) ?></span>
        <span><?= money((float) $i['price'] * $i['qty'], $config['currency']) ?></span>
      </div>
    <?php endforeach; ?>
    <div class="summary-row"><span>Delivery fee</span><span><?= money($fee, $config['currency']) ?></span></div>
    <div class="summary-row total"><span>Total</span><span><?= money($total, $config['currency']) ?></span></div>
  </aside>
</div>
