<?php

declare(strict_types=1);

/**
 * Admin controller — manage kitchens, menu items, and orders.
 *
 * Auth is a simple session flag set by login(). In a real deployment replace
 * this with a proper auth library and hashed password verification.
 */
final class AdminController
{
    public function __construct(
        private Kitchen $kitchens,
        private MenuItem $menu,
        private Order $orders,
        private array $config
    ) {}

    private function guard(): void
    {
        if (empty($_SESSION['is_admin'])) {
            redirect(url('admin/login'));
        }
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_verify();
            $u = $_POST['username'] ?? '';
            $p = $_POST['password'] ?? '';
            if ($u === $this->config['admin']['username'] && $p === $this->config['admin']['password']) {
                $_SESSION['is_admin'] = true;
                redirect(url('admin/index'));
            }
            flash('Invalid credentials.', 'error');
        }
        view('admin/login', ['config' => $this->config, 'cartCount' => 0]);
    }

    public function logout(): void
    {
        unset($_SESSION['is_admin']);
        redirect(url(''));
    }

    public function dashboard(): void
    {
        $this->guard();
        $kitchens = $this->kitchens->all();
        $orders   = $this->orders->all();
        $revenue  = array_sum(array_map(fn($o) => in_array($o['status'], ['completed']) ? (float) $o['total'] : 0, $orders));
        view('admin/dashboard', [
            'kitchens' => $kitchens,
            'orders'   => $orders,
            'revenue'  => $revenue,
            'config'   => $this->config,
            'cartCount'=> 0,
        ]);
    }

    /* ---- Kitchens ---- */

    public function kitchens(): void
    {
        $this->guard();
        view('admin/kitchens', [
            'kitchens' => $this->kitchens->all(),
            'config'   => $this->config,
            'cartCount'=> 0,
        ]);
    }

    public function kitchenSave(): void
    {
        $this->guard();
        csrf_verify();
        $id = (string) ($_POST['id'] ?? '');
        $data = $_POST;
        if ($id && $this->kitchens->find($id)) {
            $this->kitchens->update($id, $data);
            flash('Kitchen updated.', 'success');
        } else {
            $this->kitchens->create($data);
            flash('Kitchen created.', 'success');
        }
        redirect(url('admin/kitchens'));
    }

    public function kitchenDelete(): void
    {
        $this->guard();
        csrf_verify();
        $ok = $this->kitchens->delete((string) ($_POST['id'] ?? ''));
        flash($ok ? 'Kitchen deleted.' : 'Cannot delete a kitchen that still has menu items.', $ok ? 'success' : 'error');
        redirect(url('admin/kitchens'));
    }

    /* ---- Menu items ---- */

    public function menu(): void
    {
        $this->guard();
        $kitchenId = (string) ($_GET['kitchen_id'] ?? '');
        $items = $kitchenId ? $this->menu->byKitchen($kitchenId) : $this->menu->all();
        view('admin/menu', [
            'items'    => $items,
            'kitchens' => $this->kitchens->all(),
            'filter'   => $kitchenId,
            'config'   => $this->config,
            'cartCount'=> 0,
        ]);
    }

    public function menuSave(): void
    {
        $this->guard();
        csrf_verify();
        $id = (string) ($_POST['id'] ?? '');
        $data = $_POST;
        if ($id && $this->menu->find($id)) {
            $this->menu->update($id, $data);
            flash('Menu item updated.', 'success');
        } else {
            $this->menu->create($data);
            flash('Menu item created.', 'success');
        }
        redirect(url('admin/menu'));
    }

    public function menuDelete(): void
    {
        $this->guard();
        csrf_verify();
        $this->menu->delete((string) ($_POST['id'] ?? ''));
        flash('Menu item deleted.', 'success');
        redirect(url('admin/menu'));
    }

    /* ---- Orders ---- */

    public function orders(): void
    {
        $this->guard();
        $status = (string) ($_GET['status'] ?? '');
        $orders = $this->orders->all();
        if ($status) {
            $orders = array_filter($orders, fn($o) => $o['status'] === $status);
        }
        view('admin/orders', [
            'orders'   => array_values($orders),
            'statuses' => $this->config['statuses'],
            'filter'   => $status,
            'config'   => $this->config,
            'cartCount'=> 0,
        ]);
    }

    public function orderStatus(): void
    {
        $this->guard();
        csrf_verify();
        $id = (string) ($_POST['id'] ?? '');
        $status = (string) ($_POST['status'] ?? '');
        if (!in_array($status, $this->config['statuses'], true)) {
            flash('Unknown status.', 'error');
            redirect(url('admin/orders'));
            return;
        }
        $this->orders->updateStatus($id, $status);
        flash('Order status updated.', 'success');
        redirect(url('admin/orders'));
    }

    public function orderDelete(): void
    {
        $this->guard();
        csrf_verify();
        $this->orders->delete((string) ($_POST['id'] ?? ''));
        flash('Order deleted.', 'success');
        redirect(url('admin/orders'));
    }

    /**
     * Kitchen operator view: shows only the orders containing items from the
     * selected kitchen, and lets the operator advance those orders.
     */
    public function kitchenOrders(): void
    {
        $this->guard();
        $kitchenId = (string) ($_GET['kitchen_id'] ?? '');
        $kitchen = $this->kitchens->find($kitchenId);
        if (!$kitchen) {
            redirect(url('admin/kitchens'));
        }
        view('admin/kitchen_orders', [
            'kitchen'  => $kitchen,
            'orders'   => $this->orders->byKitchen($kitchenId),
            'statuses' => $this->config['statuses'],
            'config'   => $this->config,
            'cartCount'=> 0,
        ]);
    }
}
