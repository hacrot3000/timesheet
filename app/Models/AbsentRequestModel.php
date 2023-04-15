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
class AbsentRequestModel extends BaseModel
{

    //ABSENT_TYPE
    const ABSENT_TYPE_WHOLE_DAY           = 1;
    const ABSENT_TYPE_COME_LATE           = 2;
    const ABSENT_TYPE_LEAVE_EARLY         = 3;
    const ABSENT_TYPE_WORK_AT_HOME        = 4;
    const ABSENT_TYPE_ADD_PAID_LEAVE      = 5;
    const ABSENT_TYPE_FORGOT_CHECKIN      = 6;
    const ABSENT_TYPE_FORGOT_CHECKOUT     = 7;
    //APPROVE_STATUS
    const APPROVE_STATUS_WAITING          = 0;
    const APPROVE_STATUS_APPROVED         = 1;
    const APPROVE_STATUS_REJECTED         = 2;
    //LEAVE_COUNT_TYPE
    const LEAVE_COUNT_TYPE_UNKNOW         = 0;
    const LEAVE_COUNT_TYPE_PAID_LEAVE     = 1;
    const LEAVE_COUNT_TYPE_PAY_CUT        = 2;
    const LEAVE_COUNT_TYPE_IGNORE         = 3;
    const LEAVE_COUNT_TYPE_ADD_PAID_LEAVE = 4;
    const ABSENT_TYPE_NAME                = [
        self::ABSENT_TYPE_WHOLE_DAY      => "Nghỉ nguyên ngày",
        self::ABSENT_TYPE_COME_LATE      => "Đến trễ",
        self::ABSENT_TYPE_LEAVE_EARLY    => "Về sớm",
        self::ABSENT_TYPE_WORK_AT_HOME   => "Làm việc từ xa",
        self::ABSENT_TYPE_ADD_PAID_LEAVE => "Cộng thêm phép năm",
        self::ABSENT_TYPE_FORGOT_CHECKIN => "Ghi chú quên checkin",
        self::ABSENT_TYPE_FORGOT_CHECKOUT => "Ghi chú quên checkout",
    ];
    const APPROVE_STATUS_NAME             = [
        self::APPROVE_STATUS_WAITING  => "Chờ duyệt",
        self::APPROVE_STATUS_APPROVED => "Đã duyệt",
        self::APPROVE_STATUS_REJECTED => "Từ chối",
    ];
    const LEAVE_COUNT_NAME                = [
        self::LEAVE_COUNT_TYPE_UNKNOW         => "Chưa xác định",
        self::LEAVE_COUNT_TYPE_PAID_LEAVE     => "Trừ phép",
        self::LEAVE_COUNT_TYPE_PAY_CUT        => "Trừ lương",
        self::LEAVE_COUNT_TYPE_IGNORE         => "Chỉ ghi chú",
        self::LEAVE_COUNT_TYPE_ADD_PAID_LEAVE => "Cộng thêm phép năm",
    ];

    protected $table            = 'absent_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    // protected $returnType         = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['user_id', 'request_date', 'absent_type', 'approve_status', 'leave_count_type', 'request_group'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation   = false;

    public function findByUser_id($userId, $startDateData, $endDateData, $type = 0)
    {
        $this->select("DATE_FORMAT(`request_date`, '%d-%m-%Y') `request_date`, absent_type, approve_status, leave_count_type, created_at, updated_at", false);
        $this->where("request_date BETWEEN '{$startDateData}' AND '{$endDateData}'");
        
        if (!empty($type))
        {
            $this->where("absent_type", $type);
        }

        return parent::findByUser_id($userId);
    }

    public function findByMonth($startDateData, $endDateData, $showOnlyNotApproved, $showOnlyApproved = false, $team = '')
    {
        if (!empty($startDateData) && !empty($endDateData))
        {
            $this->where("request_date BETWEEN '{$startDateData}' AND '{$endDateData}'");
        }

        if ($showOnlyNotApproved)
        {
            $this->groupStart();
            $this->orWhere("approve_status", self::APPROVE_STATUS_WAITING);
            $this->orWhere("absent_type", self::ABSENT_TYPE_COME_LATE);
            $this->orWhere("absent_type", self::ABSENT_TYPE_LEAVE_EARLY);
            $this->groupEnd();
        }
        
        if (!empty($team))
        {
            $this->where("users.team", $team);
        }
        
        if ($showOnlyApproved)
        {
            $this->where("approve_status", self::APPROVE_STATUS_APPROVED);
        }

        $this->select("absent_requests.id, user_id, fullname, DATE_FORMAT(`request_date`, '%d-%m-%Y') `request_date`, absent_type, approve_status, leave_count_type", false);

        $this->join('users', 'absent_requests.user_id = users.id');
        $this->orderBy('fullname, request_date');
        return parent::findAll();
    }

    public static function isNotWorkingDay($date)
    {
        //$dayOfWeek = intval(date("w", $date));
        $dayOfWeek = $date->format('w');

        if ($dayOfWeek == 0 || $dayOfWeek == 6)
        {
            return true;
        }

        return false;
    }

}
