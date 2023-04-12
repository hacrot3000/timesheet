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
class IplogsModel extends BaseModel
{

    private const TIMEOUT      = 60;
    private const DATA_TIMEOUT = 120;

    protected $table            = 'iplogs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    // protected $returnType         = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['id', 'user_id', 'step', 'valid_until', 'ip'];
    // protected $useTimestamps      = true;
    protected $createdField     = '';
    protected $updatedField     = '';
    protected $deletedField     = '';
    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;

    private $settings = array();

    public function __construct(?ConnectionInterface $db = null, ?ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
    }

    public function insert($userId = null, bool $returnID = true)
    {
        if (!empty($userId))
        {
            $current = $this->findFirstByUser_id($userId);

            if (!empty($current))
            {
                $this->delete($current['id']);
            }
        }

        $data['id']          = md5(uniqid());
        $data['user_id']     = $userId;
        $data['valid_until'] = time() + self::TIMEOUT;
        $data['step']        = 1;
        parent::insert($data, false);

        return $data['id'];
    }

    public function update($id = null, $data = null): bool
    {
        if (empty($data))
        {
            $data = $id;
            $id   = $data['id'];
        }
        $data['step']        = 2;
        $data['valid_until'] = time() + self::DATA_TIMEOUT;
        return parent::update($id, $data);
    }
}
