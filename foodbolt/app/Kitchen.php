<?php

declare(strict_types=1);

/**
 * Kitchen model — represents one food vendor in the multi-kitchen marketplace.
 */
final class Kitchen
{
    public function __construct(private Storage $db) {}

    public function all(): array
    {
        $rows = $this->db->all('kitchens');
        usort($rows, fn($a, $b) => strcmp($a['name'] ?? '', $b['name'] ?? ''));
        return $rows;
    }

    public function active(): array
    {
        return array_values(array_filter($this->all(), fn($k) => !empty($k['active'])));
    }

    public function find(string $id): ?array
    {
        return $this->db->find('kitchens', $id);
    }

    public function create(array $data): array
    {
        return $this->db->insert('kitchens', [
            'name'        => trim($data['name'] ?? ''),
            'description' => trim($data['description'] ?? ''),
            'image'       => trim($data['image'] ?? ''),
            'cuisine'     => trim($data['cuisine'] ?? ''),
            'active'      => (bool) ($data['active'] ?? true),
        ]);
    }

    public function update(string $id, array $data): ?array
    {
        $fields = [];
        foreach (['name', 'description', 'image', 'cuisine'] as $f) {
            if (array_key_exists($f, $data)) $fields[$f] = trim($data[$f]);
        }
        if (array_key_exists('active', $data)) $fields['active'] = (bool) $data['active'];
        return $this->db->update('kitchens', $id, $fields);
    }

    public function delete(string $id): bool
    {
        // Refuse to delete a kitchen that still has menu items or open orders.
        if ($this->db->find('kitchens', $id) === null) return false;
        $items = array_filter($this->db->all('menu_items'), fn($i) => ($i['kitchen_id'] ?? '') === $id);
        if ($items) return false;
        return $this->db->delete('kitchens', $id);
    }
}
