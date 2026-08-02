<?php /** @var array $items, array $kitchens, string $filter, array $config */
$edit = null;
if (!empty($_GET['edit'])) {
  foreach ($items as $i) if ($i['id'] === $_GET['edit']) $edit = $i;
}
?>
<div class="admin-layout">
  <?php partial('admin/_sidebar', ['active' => 'menu', 'config' => $config]); ?>
  <div class="admin-content">
    <div class="section-title">
      <h1 style="font-size:1.6rem;font-weight:800;">Menu items</h1>
      <div style="display:flex;gap:8px;">
        <form method="get" style="display:flex;gap:6px;">
          <select name="kitchen_id" onchange="this.form.submit()" style="padding:8px;border-radius:8px;border:1px solid var(--neutral-300);">
            <option value="">All kitchens</option>
            <?php foreach ($kitchens as $k): ?>
              <option value="<?= e($k['id']) ?>" <?= $filter === $k['id'] ? 'selected' : '' ?>><?= e($k['name']) ?></option>
            <?php endforeach; ?>
          </select>
          <input type="hidden" name="r" value="admin/menu">
        </form>
        <a href="<?= e(url('admin/menu')) ?>" class="btn btn-primary btn-sm"><?= $edit ? '← Cancel' : '+ New item' ?></a>
      </div>
    </div>

    <?php if ($edit || !isset($_GET['list'])): ?>
      <div class="form-card" style="max-width:none;margin-bottom:28px;">
        <h3 style="margin-bottom:16px;"><?= $edit ? 'Edit menu item' : 'Add a menu item' ?></h3>
        <form action="<?= e(url('admin/menu/save')) ?>" method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
          <div class="form-row">
            <div class="form-group"><label>Name</label><input type="text" name="name" required value="<?= e($edit['name'] ?? '') ?>"></div>
            <div class="form-group"><label>Kitchen</label>
              <select name="kitchen_id" required>
                <?php foreach ($kitchens as $k): ?>
                  <option value="<?= e($k['id']) ?>" <?= ($edit['kitchen_id'] ?? '') === $k['id'] ? 'selected' : '' ?>><?= e($k['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group"><label>Price</label><input type="number" step="0.01" min="0" name="price" required value="<?= e($edit['price'] ?? '') ?>"></div>
            <div class="form-group"><label>Category</label><input type="text" name="category" value="<?= e($edit['category'] ?? 'Main') ?>"></div>
          </div>
          <div class="form-group"><label>Image URL</label><input type="url" name="image" value="<?= e($edit['image'] ?? '') ?>"></div>
          <div class="form-group"><label>Description</label><textarea name="description"><?= e($edit['description'] ?? '') ?></textarea></div>
          <div class="form-group"><label><input type="checkbox" name="available" value="1" <?= empty($edit) || !empty($edit['available']) ? 'checked' : '' ?>> Available</label></div>
          <button type="submit" class="btn btn-primary"><?= $edit ? 'Update item' : 'Create item' ?></button>
        </form>
      </div>
    <?php endif; ?>

    <div class="table-wrap">
      <table>
        <thead><tr><th>Item</th><th>Kitchen</th><th>Category</th><th>Price</th><th>Available</th><th>Actions</th></tr></thead>
        <tbody>
          <?php if (!$items): ?>
            <tr><td colspan="6" style="text-align:center;color:var(--neutral-400);">No menu items yet.</td></tr>
          <?php else: foreach ($items as $i):
            $kn = ''; foreach ($kitchens as $k) if ($k['id'] === $i['kitchen_id']) $kn = $k['name']; ?>
            <tr>
              <td><strong><?= e($i['name']) ?></strong></td>
              <td><?= e($kn) ?></td>
              <td><?= e($i['category']) ?></td>
              <td><?= money((float) $i['price'], $config['currency']) ?></td>
              <td><?= !empty($i['available']) ? '<span class="badge badge-ready">Yes</span>' : '<span class="badge badge-cancelled">No</span>' ?></td>
              <td>
                <a href="<?= e(url('admin/menu?edit=' . $i['id'] . '&kitchen_id=' . $filter)) ?>" class="btn btn-outline btn-sm">Edit</a>
                <form action="<?= e(url('admin/menu/delete')) ?>" method="post" class="inline-form" onsubmit="return confirm('Delete this item?')">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= e($i['id']) ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
