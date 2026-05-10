<?php
namespace App\Models;
use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table      = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'regime_id', 'price_paid'];

    public function hasOrdered(int $userId, int $regimeId): bool
    {
        return $this->where('user_id', $userId)
                    ->where('regime_id', $regimeId)
                    ->countAllResults() > 0;
    }
}