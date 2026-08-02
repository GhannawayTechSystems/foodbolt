<?php

declare(strict_types=1);

/**
 * Public-facing controllers: browse kitchens, view a kitchen's menu, manage
 * the cart, and place an order.
 */
final class PublicController
{
    public function __construct(
        private Kitchen $kitchens,
        private MenuItem $menu,
        private Cart $cart,
        private Order $orders,
        private array $config
    ) {}

    public function home(): void
    {
        $kitchens = $this->kitchens->active();
        view('home', [
            'kitchens' => $kitchens,
            'cartCount'=> $this->cart->count(),
            'config'   => $this->config,
        ]);
    }

    public function kitchen(): void
    {
        $id = (string) ($_GET['id'] ?? '');
        $kitchen = $this->kitchens->find($id);
        if (!$kitchen) {
            http_response_code(404);
            view('errors/404', ['path' => 'kitchen']);
            return;
        }
        $items = $this->menu->byKitchen($id);
        $byCategory = [];
        foreach ($items as $item) {
            $byCategory[$item['category'] ?? 'Main'][] = $item;
        }
        view('kitchen', [
            'kitchen'    => $kitchen,
            'byCategory' => $byCategory,
            'cartCount'  => $this->cart->count(),
            'config'     => $this->config,
        ]);
    }

    public function cartAdd(): void
    {
        csrf_verify();
        $itemId = (string) ($_POST['item_id'] ?? '');
        $item = $this->menu->find($itemId);
        if (!$item || empty($item['available'])) {
            flash('That item is no longer available.', 'error');
            redirect(url('kitchen?id=' . ($_POST['kitchen_id'] ?? '')));
        }
        $kitchen = $this->kitchens->find($item['kitchen_id']);
        $this->cart->add(
            $itemId,
            $item['kitchen_id'],
            $item['name'],
            (float) $item['price'],
            $item['image'] ?? '',
            $kitchen['name'] ?? 'Unknown',
            max(1, (int) ($_POST['qty'] ?? 1))
        );
        flash('Added ' . $item['name'] . ' to your cart.', 'success');
        redirect(url('cart/index'));
    }

    public function cart(): void
    {
        view('cart', [
            'items'   => $this->cart->items(),
            'subtotal'=> $this->cart->subtotal(),
            'fee'     => $this->cart->deliveryFee(),
            'total'   => $this->cart->total(),
            'cartCount'=> $this->cart->count(),
            'config'  => $this->config,
        ]);
    }

    public function cartUpdate(): void
    {
        csrf_verify();
        $itemId = (string) ($_POST['item_id'] ?? '');
        $this->cart->updateQty($itemId, max(0, (int) ($_POST['qty'] ?? 0)));
        redirect(url('cart/index'));
    }

    public function cartRemove(): void
    {
        csrf_verify();
        $this->cart->remove((string) ($_POST['item_id'] ?? ''));
        redirect(url('cart/index'));
    }

    public function checkout(): void
    {
        if (!$this->cart->items()) {
            flash('Your cart is empty.', 'info');
            redirect(url(''));
        }
        view('checkout', [
            'items'   => $this->cart->items(),
            'subtotal'=> $this->cart->subtotal(),
            'fee'     => $this->cart->deliveryFee(),
            'total'   => $this->cart->total(),
            'cartCount'=> $this->cart->count(),
            'config'  => $this->config,
        ]);
    }

    public function placeOrder(): void
    {
        csrf_verify();
        if (!$this->cart->items()) {
            flash('Your cart is empty.', 'error');
            redirect(url(''));
        }
        $order = $this->orders->create([
            'customer_name'    => $_POST['customer_name'] ?? '',
            'customer_phone'   => $_POST['customer_phone'] ?? '',
            'customer_address' => $_POST['customer_address'] ?? '',
            'notes'            => $_POST['notes'] ?? '',
            'items'            => array_values($this->cart->items()),
            'subtotal'         => $this->cart->subtotal(),
            'delivery_fee'     => $this->cart->deliveryFee(),
            'total'            => $this->cart->total(),
            'status'           => $this->config['default_status'],
        ]);
        $this->cart->clear();
        flash('Order placed! Your order number is ' . substr($order['id'], 0, 8) . '.', 'success');
        redirect(url('order/show?id=' . urlencode($order['id'])));
    }

    public function order(): void
    {
        $id = (string) ($_GET['id'] ?? '');
        $order = $this->orders->find($id);
        if (!$order) {
            http_response_code(404);
            view('errors/404', ['path' => 'order']);
            return;
        }
        view('order', [
            'order'    => $order,
            'cartCount'=> $this->cart->count(),
            'config'   => $this->config,
        ]);
    }
}
