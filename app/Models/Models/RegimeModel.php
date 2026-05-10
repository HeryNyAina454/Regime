<?php
namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table      = 'regimes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'description', 'price', 'duration_days',
        'goal', 'meat_pct', 'fish_pct', 'poultry_pct', 'weight_change_kg'
    ];

    public function getByGoal(string $goal): array
    {
        return $this->where('goal', $goal)->findAll();
    }

    public function getAllWithPricing(): array
    {
        $db      = \Config\Database::connect();
        $regimes = $this->findAll();

        foreach ($regimes as &$regime) {
            $regime['pricing'] = $db->query(
                "SELECT * FROM regime_pricing WHERE regime_id = ? ORDER BY duration_days ASC",
                [$regime['id']]
            )->getResultArray();
        }

        return $regimes;
    }

    public function getWithPricing(int $id): ?array
    {
        $regime = $this->find($id);
        if (!$regime) return null;

        $db = \Config\Database::connect();
        $regime['pricing'] = $db->query(
            "SELECT * FROM regime_pricing WHERE regime_id = ? ORDER BY duration_days ASC",
            [$id]
        )->getResultArray();

        return $regime;
    }
}