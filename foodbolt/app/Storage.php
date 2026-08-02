<?php

declare(strict_types=1);

/**
 * JSON-file storage layer.
 *
 * Each "table" is a JSON file under storage/. Reads load the whole file into
 * memory; writes persist back atomically. This keeps the app dependency-free
 * and trivially portable. Swap this class for a PDO-backed implementation if
 * you outgrow it — the model layer above is the only consumer.
 */
final class Storage
{
    private string $dir;
    private array $cache = [];

    public function __construct(string $dir)
    {
        $this->dir = rtrim($dir, '/\\');
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0777, true);
        }
    }

    private function path(string $table): string
    {
        return $this->dir . '/' . $table . '.json';
    }

    public function all(string $table): array
    {
        if (isset($this->cache[$table])) {
            return $this->cache[$table];
        }
        $path = $this->path($table);
        if (!is_file($path)) {
            return $this->cache[$table] = [];
        }
        $data = json_decode((string) file_get_contents($path), true);
        return $this->cache[$table] = is_array($data) ? $data : [];
    }

    public function find(string $table, string $id): ?array
    {
        foreach ($this->all($table) as $row) {
            if (($row['id'] ?? null) === $id) {
                return $row;
            }
        }
        return null;
    }

    public function insert(string $table, array $row): array
    {
        $row['id']      = $row['id'] ?? $this->uuid();
        $row['created_at'] = $row['created_at'] ?? date('c');
        $row['updated_at'] = date('c');
        $rows = $this->all($table);
        $rows[] = $row;
        $this->write($table, $rows);
        return $row;
    }

    public function update(string $table, string $id, array $data): ?array
    {
        $rows = $this->all($table);
        foreach ($rows as $i => $row) {
            if (($row['id'] ?? null) === $id) {
                $rows[$i] = array_merge($row, $data, ['updated_at' => date('c')]);
                $this->write($table, $rows);
                return $rows[$i];
            }
        }
        return null;
    }

    public function delete(string $table, string $id): bool
    {
        $rows = $this->all($table);
        foreach ($rows as $i => $row) {
            if (($row['id'] ?? null) === $id) {
                unset($rows[$i]);
                $this->write($table, array_values($rows));
                return true;
            }
        }
        return false;
    }

    private function write(string $table, array $rows): void
    {
        $path = $this->path($table);
        $tmp  = $path . '.tmp';
        file_put_contents($tmp, json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
        rename($tmp, $path);
        $this->cache[$table] = $rows;
    }

    private function uuid(): string
    {
        $b = random_bytes(16);
        $b[6] = chr((ord($b[6]) & 0x0f) | 0x40);
        $b[8] = chr((ord($b[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($b), 4));
    }
}
