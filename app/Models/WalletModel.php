<?php
namespace App\Models;
use CodeIgniter\Model;

class WalletModel extends Model
{
    protected $table      = 'wallets';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'balance', 'is_gold'];

    public function getByUser(int $userId): ?array
    {
        return $this->where('user_id', $userId)->first();
    }

    public function initForUser(int $userId): void
    {
        if (!$this->getByUser($userId)) {
            $this->insert(['user_id' => $userId, 'balance' => 0.00, 'is_gold' => false]);
        }
    }
}