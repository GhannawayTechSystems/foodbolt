<?php /** @var array $orders, array $statuses, string $filter, array $config */ ?>
<div class="admin-layout">
  <?php partial('admin/_sidebar', ['active' => 'orders', 'config' => $config]); ?>
  <div class="admin-content">
    <div class="section-title">
      <h1 style="font-size:1.6rem;font-weight:800;">Orders</h1>
    </div>

    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px;">
      <a href="<?= e(url('admin/orders')) ?>" class="btn btn-outline btn-sm <?= !$filter ? 'btn-primary' : '' ?>">All</a>
      <?php foreach ($statuses as $s): ?>
        <a href="<?= e(url('admin/orders?status=' . $s)) ?>" class="btn btn-outline btn-sm <?= $filter === $s ? 'btn-primary' : '' ?>"><?= e(ucfirst($s)) ?></a>
      <?php endforeach; ?>
    </div>

    <div class="table-wrap">
      <table>
        <thead><tr><th>Order</th><th>Customer</th><th>Phone</th><th>Items</th><th>Total</th><th>Status</th><th>Update</th><th></th></tr></thead>
        <tbody>
          <?php if (!$orders): ?>
            <tr><td colspan="8" style="text-align:center;color:var(--neutral-400);">No orders.</td></tr>
          <?php else: foreach ($orders as $o): ?>
            <tr>
              <td><a href="<?= e(url('order/show?id=' . $o['id'])) ?>"><?= e(strtoupper(substr($o['id'], 0, 8))) ?></a><br><small style="color:var(--neutral-400);"><?= e(date('M j, g:ia', strtotime($o['created_at']))) ?></small></td>
              <td><?= e($o['customer_name']) ?></td>
              <td><?= e($o['customer_phone']) ?></td>
              <td><?= count($o['items'] ?? []) ?></td>
              <td><?= money((float) $o['total'], $config['currency']) ?></td>
              <td><span class="badge badge-<?= e($o['status']) ?>"><?= e(ucfirst($o['status'])) ?></span></td>
              <td>
                <form action="<?= e(url('admin/order/status')) ?>" method="post" class="inline-form" style="display:flex;gap:4px;">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= e($o['id']) ?>">
                  <select name="status" onchange="this.form.submit()" style="padding:5px 8px;border-radius:6px;border:1px solid var(--neutral-300);font-size:.82rem;">
                    <?php foreach ($statuses as $s): ?>
                      <option value="<?= e($s) ?>" <?= $o['status'] === $s ? 'selected' : '' ?>><?= e(ucfirst($s)) ?></option>
                    <?php endforeach; ?>
                  </select>
                </form>
              </td>
              <td>
                <form action="<?= e(url('admin/order/delete')) ?>" method="post" class="inline-form" onsubmit="return confirm('Delete this order?')">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= e($o['id']) ?>">
                  <button type="submit" class="btn btn-danger btn-sm">×</button>
                </form>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
