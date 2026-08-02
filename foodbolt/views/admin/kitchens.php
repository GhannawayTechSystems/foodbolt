<?php /** @var array $kitchens, array $config */
$edit = null;
if (!empty($_GET['edit'])) {
  foreach ($kitchens as $k) if ($k['id'] === $_GET['edit']) $edit = $k;
}
?>
<div class="admin-layout">
  <?php partial('admin/_sidebar', ['active' => 'kitchens', 'config' => $config]); ?>
  <div class="admin-content">
    <div class="section-title">
      <h1 style="font-size:1.6rem;font-weight:800;">Kitchens</h1>
      <a href="<?= e(url('admin/kitchens')) ?>" class="btn btn-primary btn-sm"><?= $edit ? '← Cancel edit' : '+ New kitchen' ?></a>
    </div>

    <?php if ($edit || !isset($_GET['list'])): ?>
      <div class="form-card" style="max-width:none;margin-bottom:28px;">
        <h3 style="margin-bottom:16px;"><?= $edit ? 'Edit kitchen' : 'Add a new kitchen' ?></h3>
        <form action="<?= e(url('admin/kitchen/save')) ?>" method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
          <div class="form-row">
            <div class="form-group"><label>Name</label><input type="text" name="name" required value="<?= e($edit['name'] ?? '') ?>"></div>
            <div class="form-group"><label>Cuisine</label><input type="text" name="cuisine" value="<?= e($edit['cuisine'] ?? '') ?>"></div>
          </div>
          <div class="form-group"><label>Image URL</label><input type="url" name="image" value="<?= e($edit['image'] ?? '') ?>"></div>
          <div class="form-group"><label>Description</label><textarea name="description"><?= e($edit['description'] ?? '') ?></textarea></div>
          <div class="form-group">
            <label><input type="checkbox" name="active" value="1" <?= empty($edit) || !empty($edit['active']) ? 'checked' : '' ?>> Active (visible to customers)</label>
          </div>
          <button type="submit" class="btn btn-primary"><?= $edit ? 'Update kitchen' : 'Create kitchen' ?></button>
        </form>
      </div>
    <?php endif; ?>

    <div class="table-wrap">
      <table>
        <thead><tr><th>Name</th><th>Cuisine</th><th>Active</th><th>Actions</th></tr></thead>
        <tbody>
          <?php if (!$kitchens): ?>
            <tr><td colspan="4" style="text-align:center;color:var(--neutral-400);">No kitchens yet.</td></tr>
          <?php else: foreach ($kitchens as $k): ?>
            <tr>
              <td><strong><?= e($k['name']) ?></strong></td>
              <td><?= e($k['cuisine']) ?></td>
              <td><?= !empty($k['active']) ? '<span class="badge badge-ready">Yes</span>' : '<span class="badge badge-cancelled">No</span>' ?></td>
              <td>
                <a href="<?= e(url('admin/kitchen/orders?kitchen_id=' . $k['id'])) ?>" class="btn btn-ghost btn-sm">Orders</a>
                <a href="<?= e(url('admin/kitchens?edit=' . $k['id'])) ?>" class="btn btn-outline btn-sm">Edit</a>
                <form action="<?= e(url('admin/kitchen/delete')) ?>" method="post" class="inline-form" onsubmit="return confirm('Delete this kitchen?')">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= e($k['id']) ?>">
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
