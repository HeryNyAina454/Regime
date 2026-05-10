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
}