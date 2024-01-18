<?php

/*
 */

namespace App\Models;

use CodeIgniter\Model;
use App\Models\BaseModel;

/**
 * Description of Users
 *
 * @author duongtc
 */
class ClaimFormModel extends BaseModel
{

    protected $table            = 'claim_form';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    // protected $returnType         = 'array';
    // protected $useSoftDeletes     = true;
    protected $allowedFields    = ['user_id', 'inum', 'data'];

    // protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';
    protected $deletedField       = 'deleted_at';
    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;

    public function findByUserAndInum($userId, $inum)
    {
        return $this
            ->where('user_id', $userId)
            ->where('inum', $inum)
            ->first();
    }
}
