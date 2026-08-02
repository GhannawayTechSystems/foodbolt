<?php /** @var array $kitchens, array $orders, float $revenue, array $config */ ?>
<div class="admin-layout">
  <?php partial('admin/_sidebar', ['active' => 'dashboard', 'config' => $config]); ?>
  <div class="admin-content">
    <h1 style="font-size:1.6rem;font-weight:800;margin-bottom:24px;">Dashboard</h1>
    <div class="stat-grid">
      <div class="stat-card"><div class="label">Kitchens</div><div class="value"><?= count($kitchens) ?></div></div>
      <div class="stat-card"><div class="label">Total orders</div><div class="value"><?= count($orders) ?></div></div>
      <div class="stat-card"><div class="label">Completed revenue</div><div class="value"><?= money($revenue, $config['currency']) ?></div></div>
      <div class="stat-card"><div class="label">Pending</div><div class="value"><?= count(array_filter($orders, fn($o) => in_array($o['status'], ['pending','preparing','ready']))) ?></div></div>
    </div>

    <h2 style="font-size:1.2rem;font-weight:700;margin-bottom:14px;">Recent orders</h2>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Order</th><th>Customer</th><th>Items</th><th>Total</th><th>Status</th></tr></thead>
        <tbody>
          <?php if (!$orders): ?>
            <tr><td colspan="5" style="text-align:center;color:var(--neutral-400);">No orders yet.</td></tr>
          <?php else: foreach (array_slice($orders, 0, 8) as $o): ?>
            <tr>
              <td><a href="<?= e(url('order/show?id=' . $o['id'])) ?>"><?= e(strtoupper(substr($o['id'], 0, 8))) ?></a></td>
              <td><?= e($o['customer_name']) ?></td>
              <td><?= count($o['items'] ?? []) ?></td>
              <td><?= money((float) $o['total'], $config['currency']) ?></td>
              <td><span class="badge badge-<?= e($o['status']) ?>"><?= e(ucfirst($o['status'])) ?></span></td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
