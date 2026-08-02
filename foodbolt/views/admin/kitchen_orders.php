<?php /** @var array $kitchen, array $orders, array $statuses, array $config */ ?>
<div class="admin-layout">
  <?php partial('admin/_sidebar', ['active' => 'kitchens', 'config' => $config]); ?>
  <div class="admin-content">
    <div class="section-title">
      <h1 style="font-size:1.6rem;font-weight:800;">Orders for <?= e($kitchen['name']) ?></h1>
      <a href="<?= e(url('admin/kitchens')) ?>" class="btn btn-ghost btn-sm">← All kitchens</a>
    </div>

    <p style="color:var(--neutral-500);margin-bottom:20px;">Only the items from this kitchen are shown. The customer's full order may include dishes from other kitchens too.</p>

    <div class="table-wrap">
      <table>
        <thead><tr><th>Order</th><th>Customer</th><th>Items (this kitchen)</th><th>Subtotal</th><th>Status</th><th>Update</th></tr></thead>
        <tbody>
          <?php if (!$orders): ?>
            <tr><td colspan="6" style="text-align:center;color:var(--neutral-400);">No orders for this kitchen yet.</td></tr>
          <?php else: foreach ($orders as $o):
            $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $o['items'])); ?>
            <tr>
              <td><a href="<?= e(url('order/show?id=' . $o['id'])) ?>"><?= e(strtoupper(substr($o['id'], 0, 8))) ?></a><br><small style="color:var(--neutral-400);"><?= e(date('M j, g:ia', strtotime($o['created_at']))) ?></small></td>
              <td><?= e($o['customer_name']) ?><br><small style="color:var(--neutral-400);"><?= e($o['customer_phone']) ?></small></td>
              <td>
                <?php foreach ($o['items'] as $i): ?>
                  <?= (int) $i['qty'] ?>× <?= e($i['name']) ?><br>
                <?php endforeach; ?>
              </td>
              <td><?= money($subtotal, $config['currency']) ?></td>
              <td><span class="badge badge-<?= e($o['status']) ?>"><?= e(ucfirst($o['status'])) ?></span></td>
              <td>
                <form action="<?= e(url('admin/order/status')) ?>" method="post" class="inline-form">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= e($o['id']) ?>">
                  <select name="status" onchange="this.form.submit()" style="padding:5px 8px;border-radius:6px;border:1px solid var(--neutral-300);font-size:.82rem;">
                    <?php foreach ($statuses as $s): ?>
                      <option value="<?= e($s) ?>" <?= $o['status'] === $s ? 'selected' : '' ?>><?= e(ucfirst($s)) ?></option>
                    <?php endforeach; ?>
                  </select>
                </form>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
