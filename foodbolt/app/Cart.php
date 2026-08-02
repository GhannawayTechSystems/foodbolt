<?php

declare(strict_types=1);

/**
 * Session-based shopping cart supporting items from multiple kitchens.
 *
 * The cart is stored in $_SESSION['cart'] as an array of line items, each
 * keyed by menu item id. Quantities add up; the same dish from the same
 * kitchen never appears twice.
 */
final class Cart
{
    private function &session(): array
    {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        return $_SESSION['cart'];
    }

    public function add(string $itemId, string $kitchenId, string $name, float $price, string $image, string $kitchenName, int $qty = 1): void
    {
        $cart = &$this->session();
        if (isset($cart[$itemId])) {
            $cart[$itemId]['qty'] += $qty;
        } else {
            $cart[$itemId] = [
                'item_id'     => $itemId,
                'kitchen_id'  => $kitchenId,
                'kitchen_name'=> $kitchenName,
                'name'        => $name,
                'price'       => $price,
                'image'       => $image,
                'qty'         => $qty,
            ];
        }
        unset($cart);
    }

    public function updateQty(string $itemId, int $qty): void
    {
        $cart = &$this->session();
        if ($qty <= 0) {
            unset($cart[$itemId]);
        } elseif (isset($cart[$itemId])) {
            $cart[$itemId]['qty'] = $qty;
        }
        unset($cart);
    }

    public function remove(string $itemId): void
    {
        $cart = &$this->session();
        unset($cart[$itemId]);
        unset($cart);
    }

    public function clear(): void
    {
        $_SESSION['cart'] = [];
    }

    public function items(): array
    {
        return $this->session();
    }

    public function count(): int
    {
        return array_sum(array_map(fn($i) => $i['qty'], $this->items()));
    }

    public function subtotal(): float
    {
        return array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $this->items()));
    }

    public function kitchens(): array
    {
        $names = [];
        foreach ($this->items() as $i) {
            $names[$i['kitchen_id']] = $i['kitchen_name'];
        }
        return $names;
    }

    public function deliveryFee(float $perKitchen = 2.50): float
    {
        return count($this->kitchens()) * $perKitchen;
    }

    public function total(): float
    {
        return $this->subtotal() + $this->deliveryFee();
    }
}
