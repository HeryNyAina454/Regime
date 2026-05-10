<?php
namespace App\Models;

use CodeIgniter\Model;

class RegimePricingModel extends Model
{
    protected $table      = 'regime_pricing';
    protected $primaryKey = 'id';
    protected $allowedFields = ['regime_id', 'duration_days', 'price'];

    public function deleteByRegime(int $regimeId): void
    {
        $this->where('regime_id', $regimeId)->delete();
    }

    public function insertPricing(int $regimeId, array $durations, array $prices): void
    {
        $this->deleteByRegime($regimeId);
        foreach ($durations as $i => $days) {
            if (!empty($days) && isset($prices[$i]) && $prices[$i] > 0) {
                $this->insert([
                    'regime_id'    => $regimeId,
                    'duration_days'=> (int) $days,
                    'price'        => (float) $prices[$i],
                ]);
            }
        }
    }
}