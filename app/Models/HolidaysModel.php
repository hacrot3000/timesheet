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
class HolidaysModel extends BaseModel
{

    public const DATE_STYLE_NORMAL    = 'table-active';
    public const DATE_STYLE_WEEKEND   = 'table-dark';
    public const DATE_STYLE_ABSENT    = 'table-danger';
    public const DATE_STYLE_COME_LATE = 'table-warning';

    protected $table            = 'holidays';
    protected $primaryKey       = 'day';
    protected $useAutoIncrement = true;
    // protected $returnType         = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['day', 'month'];
    // protected $useTimestamps      = true;
    protected $createdField     = '';
    protected $updatedField     = '';
    protected $deletedField     = '';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;

    public function __construct(?ConnectionInterface $db = null, ?ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
    }

    public function del($day, $month)
    {
        $this
                ->where("month", $month)
                ->delete($day);
    }
}
