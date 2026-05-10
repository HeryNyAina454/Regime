<?php
namespace App\Models;

use CodeIgniter\Model;

class WalletCodeModel extends Model
{
    protected $table      = 'wallet_codes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'code', 'amount', 'is_used', 'used_by', 'used_at'
    ];

    public function findValidCode(string $code): ?array
    {
        return $this->where('code', $code)
                    ->where('is_used', false)
                    ->first();
    }

    public function markAsUsed(int $id, int $userId): void
    {
        $this->update($id, [
            'is_used' => true,
            'used_by' => $userId,
            'used_at' => date('Y-m-d H:i:s'),
        ]);
    }
}