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
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = false;
    // protected $returnType         = 'array';
    // protected $useSoftDeletes     = true;
    protected $allowedFields    = ['user_id', 'data'];

    // protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';
    protected $deletedField       = 'deleted_at';
    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;

}
