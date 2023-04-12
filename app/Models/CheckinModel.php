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
class CheckinModel extends BaseModel
{

    protected $table            = 'checkin';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = false;
    // protected $returnType         = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['date', 'time', 'user_id'];
    // protected $useTimestamps      = true;
    protected $createdField     = '';
    protected $updatedField     = '';
    protected $deletedField     = '';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;

    public function getByUser($user_id, $startDateData, $endDateData)
    {
        $where = [
            'user_id' => $user_id,
        ];

        $all = $this->asArray()
                ->select("DATE_FORMAT(`date`, '%d-%m-%Y') `date`, group_concat(DATE_FORMAT(`time`, '%H:%i')) checkPoint, count(`time`) numberOfCheckPoint", false)
                ->where($where)
                ->where("date BETWEEN '{$startDateData}' AND '{$endDateData}'")
                ->groupBy('date')
                ->orderBy('date', 'time')
                ->findAll();

        return $all;
    }

    public function getByMonth($startDateData, $endDateData)
    {
        $all = $this->asArray()
                ->select("user_id, fullname, DATE_FORMAT(`date`, '%d-%m-%Y') `date`, DATE_FORMAT(min(`time`), '%H:%i') firstCheckPoint, DATE_FORMAT(max(`time`), '%H:%i') lastCheckPoint", false)
                ->where("date BETWEEN '{$startDateData}' AND '{$endDateData}'")
                ->groupBy('user_id, date')
                ->orderBy('date', 'time')
                ->join('users', 'checkin.user_id = users.id')
                ->findAll();

        return $all;
    }

    public function isCheckedInToDay($user_id, $compareMoment)
    {
        $c = $this
                ->select('time')
                ->where('date', date('Y-m-d'))
                ->where('user_id', $user_id)
                ->first()
                ;
        
        if (empty($c))
        {
            return false;
        }
        
        $t = intval(substr($c['time'], 0, 2));
        return $t < $compareMoment;
    }

}
