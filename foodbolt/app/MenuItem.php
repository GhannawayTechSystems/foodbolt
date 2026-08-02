<?php

declare(strict_types=1);

/**
 * Menu item model — a dish belonging to one kitchen.
 */
final class MenuItem
{
    public function __construct(private Storage $db) {}

    public function all(): array
    {
        return $this->db->all('menu_items');
    }

    public function byKitchen(string $kitchenId): array
    {
        $rows = array_filter($this->all(), fn($i) => ($i['kitchen_id'] ?? '') === $kitchenId);
        return array_values($rows);
    }

    public function available(): array
    {
        return array_values(array_filter($this->all(), fn($i) => !empty($i['available'])));
    }

    public function find(string $id): ?array
    {
        return $this->db->find('menu_items', $id);
    }

    public function create(array $data): array
    {
        return $this->db->insert('menu_items', [
            'kitchen_id' => $data['kitchen_id'] ?? '',
            'name'       => trim($data['name'] ?? ''),
            'description'=> trim($data['description'] ?? ''),
            'price'      => (float) ($data['price'] ?? 0),
            'image'      => trim($data['image'] ?? ''),
            'category'   => trim($data['category'] ?? 'Main'),
            'available'  => (bool) ($data['available'] ?? true),
        ]);
    }

    public function update(string $id, array $data): ?array
    {
        $fields = [];
        foreach (['name', 'description', 'image', 'category'] as $f) {
            if (array_key_exists($f, $data)) $fields[$f] = trim($data[$f]);
        }
        if (array_key_exists('price', $data))     $fields['price'] = (float) $data['price'];
        if (array_key_exists('available', $data)) $fields['available'] = (bool) $data['available'];
        if (array_key_exists('kitchen_id', $data)) $fields['kitchen_id'] = $data['kitchen_id'];
        return $this->db->update('menu_items', $id, $fields);
    }

    public function delete(string $id): bool
    {
        return $this->db->delete('menu_items', $id);
    }
}
