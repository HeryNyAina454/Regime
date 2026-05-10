<?php
namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table      = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['key_name', 'value', 'label', 'description'];

    public function getValue(string $key, $default = null)
    {
        $row = $this->where('key_name', $key)->first();
        return $row ? $row['value'] : $default;
    }

    public function setValue(string $key, string $value): void
    {
        $row = $this->where('key_name', $key)->first();
        if ($row) {
            $this->update($row['id'], ['value' => $value]);
        } else {
            $this->insert(['key_name' => $key, 'value' => $value]);
        }
    }

    public function getAllKeyed(): array
    {
        $rows   = $this->findAll();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['key_name']] = $row;
        }
        return $result;
    }
}