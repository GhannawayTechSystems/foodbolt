<?php

declare(strict_types=1);

/**
 * Order model — a customer order containing items from one or more kitchens.
 *
 * Multi-kitchen support: an order's items are grouped by kitchen at checkout
 * time, and each kitchen sees only its own group in its order queue. The
 * overall order status is the aggregate of all kitchen groups.
 */
final class Order
{
    public function __construct(private Storage $db) {}

    public function all(): array
    {
        $rows = $this->db->all('orders');
        usort($rows, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));
        return $rows;
    }

    public function find(string $id): ?array
    {
        return $this->db->find('orders', $id);
    }

    public function create(array $data): array
    {
        return $this->db->insert('orders', [
            'customer_name'   => trim($data['customer_name'] ?? ''),
            'customer_phone'  => trim($data['customer_phone'] ?? ''),
            'customer_address'=> trim($data['customer_address'] ?? ''),
            'notes'           => trim($data['notes'] ?? ''),
            'items'           => $data['items'] ?? [],
            'subtotal'        => (float) ($data['subtotal'] ?? 0),
            'delivery_fee'    => (float) ($data['delivery_fee'] ?? 0),
            'total'           => (float) ($data['total'] ?? 0),
            'status'          => $data['status'] ?? 'pending',
        ]);
    }

    public function updateStatus(string $id, string $status): ?array
    {
        return $this->db->update('orders', $id, ['status' => $status]);
    }

    public function update(string $id, array $data): ?array
    {
        return $this->db->update('orders', $id, $data);
    }

    public function delete(string $id): bool
    {
        return $this->db->delete('orders', $id);
    }

    /**
     * Orders that include items from a given kitchen, with each order's
     * items filtered to only that kitchen's dishes.
     */
    public function byKitchen(string $kitchenId): array
    {
        $out = [];
        foreach ($this->all() as $order) {
            $items = array_filter($order['items'] ?? [], fn($i) => ($i['kitchen_id'] ?? '') === $kitchenId);
            if (!$items) continue;
            $clone = $order;
            $clone['items'] = array_values($items);
            $out[] = $clone;
        }
        return $out;
    }
}
