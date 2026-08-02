<?php /** @var array $order, array $config, int $cartCount */
$statusClass = 'badge-' . e($order['status']);
?>
<div class="order-confirm">
  <div class="check">✓</div>
  <h1 style="font-size:1.8rem;font-weight:800;margin-bottom:8px;">Order confirmed!</h1>
  <p style="color:var(--neutral-500);margin-bottom:8px;">Your order number is <strong style="color:var(--neutral-900);"><?= e(strtoupper(substr($order['id'], 0, 8))) ?></strong></p>
  <span class="badge <?= e($statusClass) ?>"><?= e(ucfirst($order['status'])) ?></span>
</div>

<div class="order-detail" style="max-width:640px;margin-left:auto;margin-right:auto;">
  <h3 style="font-size:1.2rem;font-weight:700;margin-bottom:16px;">Order details</h3>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;font-size:.92rem;">
    <div><strong>Name:</strong><br><?= e($order['customer_name']) ?></div>
    <div><strong>Phone:</strong><br><?= e($order['customer_phone']) ?></div>
    <div style="grid-column:1/-1;"><strong>Address:</strong><br><?= e($order['customer_address']) ?></div>
    <?php if (!empty($order['notes'])): ?><div style="grid-column:1/-1;"><strong>Notes:</strong><br><?= e($order['notes']) ?></div><?php endif; ?>
  </div>

  <table>
    <thead><tr><th>Item</th><th>Kitchen</th><th style="text-align:right;">Qty</th><th style="text-align:right;">Price</th></tr></thead>
    <tbody>
      <?php foreach ($order['items'] as $i): ?>
        <tr>
          <td><?= e($i['name']) ?></td>
          <td style="color:var(--neutral-500);"><?= e($i['kitchen_name']) ?></td>
          <td style="text-align:right;"><?= (int) $i['qty'] ?></td>
          <td style="text-align:right;"><?= money((float) $i['price'] * $i['qty'], $config['currency']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div style="margin-top:16px;">
    <div class="summary-row"><span>Subtotal</span><span><?= money((float) $order['subtotal'], $config['currency']) ?></span></div>
    <div class="summary-row"><span>Delivery fee</span><span><?= money((float) $order['delivery_fee'], $config['currency']) ?></span></div>
    <div class="summary-row total"><span>Total</span><span><?= money((float) $order['total'], $config['currency']) ?></span></div>
  </div>

  <div style="margin-top:24px;text-align:center;">
    <a href="<?= e(url('')) ?>" class="btn btn-primary">Place another order</a>
  </div>
</div>
