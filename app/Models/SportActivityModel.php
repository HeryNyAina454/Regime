<?php
namespace App\Models;
use CodeIgniter\Model;

class SportActivityModel extends Model
{
    protected $table      = 'sport_activities';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'description', 'duration_minutes', 'frequency_per_week', 'goal'
    ];

    public function getByGoal(string $goal): array
    {
        return $this->where('goal', $goal)->findAll();
    }
}