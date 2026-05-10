<?php

namespace App\Models;

use CodeIgniter\Model;

class UserProfileModel extends Model
{
    protected $table      = 'user_profiles';
    protected $primaryKey = 'id';

    protected $allowedFields = ['user_id', 'gender', 'weight', 'height', 'age', 'goal'];
}