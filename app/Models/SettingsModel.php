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
class SettingsModel extends BaseModel
{

    public const DATE_STYLE_NORMAL          = 'table-active';
    public const DATE_STYLE_WEEKEND         = 'table-dark';
    public const DATE_STYLE_ABSENT          = 'table-danger';
    public const DATE_STYLE_COME_LATE       = 'table-warning';
    public const DATE_STYLE_NOT_ENOUGH_TIME = 'table-warning ';

    protected $table            = 'settings';
    protected $primaryKey       = 'key';
    protected $useAutoIncrement = false;
    // protected $returnType         = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['key', 'value'];
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

        $this->settings['start_month_date']                    = 26;
        $this->settings['work_time_start']                     = "08:30";
        $this->settings['work_time_finish']                    = "18:00";
        $this->settings['latest_work_time_start']              = "09:30";
        $this->settings['break_time_start']                    = 12;
        $this->settings['break_time_finish']                   = 14;
        $this->settings['consider_late_if_not_enough_8_hours'] = true;
        $this->settings['auto_approve']                        = implode(',', [
            AbsentRequestModel::ABSENT_TYPE_COME_LATE,
            AbsentRequestModel::ABSENT_TYPE_FORGOT_CHECKIN,
            AbsentRequestModel::ABSENT_TYPE_FORGOT_CHECKOUT,
            AbsentRequestModel::ABSENT_TYPE_LEAVE_EARLY,
            AbsentRequestModel::ABSENT_TYPE_WHOLE_DAY,
            AbsentRequestModel::ABSENT_TYPE_WORK_AT_HOME,
        ]);
        $this->settings['office_ips']                          = '113.161.70.146,42.118.116.135';
        //Email
        $this->settings['email_protocol']                      = 'smtp';
        $this->settings['email_SMTPHost']                      = 'mx.568e.vn';
        $this->settings['email_SMTPUser']                      = 'duongtc@568e.vn';
        $this->settings['email_SMTPPass']                      = '87,&,nglEviIA';
        $this->settings['email_SMTPPort']                      = 465;
        $this->settings['email_SMTPTimeout']                   = 30;
        $this->settings['email_charset']                       = 'utf-8';
        $this->settings['email_mailType']                      = 'html';
        $this->settings['email_SMTPCrypto']                    = 'ssl';
        //Email các team
        $this->settings['email_HR']                            = 'hanh@568e.vn';
        $this->settings['team']                                = 'Tech,Marketing,Design,GO,HR';
        //$this->settings['teamlead_email']                      = 'huydq@568e.vn,tuandq@568e.vn,haitt@568e.vn,hahv@568e.vn,leht@568e.vn';
        //$this->settings['teamlead_email']                      = 'duongtc@568e.vn,duongtc@568e.vn,duongtc@568e.vn,duongtc@568e.vn,duongtc@568e.vn';
    }

    public function email($subject, $content, $to)
    {
        $config = [
            'protocol'    => $this->email_protocol,
            'SMTPHost'    => $this->email_SMTPHost,
            'SMTPUser'    => $this->email_SMTPUser,
            'SMTPPass'    => $this->email_SMTPPass,
            'SMTPPort'    => $this->email_SMTPPort,
            'SMTPTimeout' => $this->email_SMTPTimeout,
            'charset'     => $this->email_charset,
            'mailType'    => $this->email_mailType,
            'SMTPCrypto'  => $this->email_SMTPCrypto,
        ];

        $email = \Config\Services::email();

        $email->initialize($config);

        $email->setFrom($this->email_SMTPUser, 'Timesheet');
        $email->setTo($to);
        $email->setCC($this->email_HR);

        $email->setSubject($subject);
        $email->setMessage($content);

        return $email->send();
    }

    public function isAutoApprove($AbsentType)
    {
        if (!in_array($AbsentType, $this->auto_approve))
            return 0;

        switch ($AbsentType)
        {
            case AbsentRequestModel::ABSENT_TYPE_ADD_PAID_LEAVE:
                return AbsentRequestModel::LEAVE_COUNT_TYPE_PAID_LEAVE;
            case AbsentRequestModel::ABSENT_TYPE_COME_LATE:
            case AbsentRequestModel::ABSENT_TYPE_FORGOT_CHECKIN:
            case AbsentRequestModel::ABSENT_TYPE_FORGOT_CHECKOUT:
            case AbsentRequestModel::ABSENT_TYPE_LEAVE_EARLY:
            case AbsentRequestModel::ABSENT_TYPE_WORK_AT_HOME:
                return AbsentRequestModel::LEAVE_COUNT_TYPE_IGNORE;
            case AbsentRequestModel::ABSENT_TYPE_WHOLE_DAY:
                return AbsentRequestModel::LEAVE_COUNT_TYPE_PAID_LEAVE;
            default:
                return AbsentRequestModel::LEAVE_COUNT_TYPE_IGNORE;
        }
    }

    public function isOfficeIp($ip)
    {
        return in_array($ip, $this->office_ips);
    }

    public function __get(string $name)
    {
        if ($name == 'teamlead_email')
        {
            return explode(',', $this->settings['teamlead_email']);
        }

        if ($name == 'team')
        {
            return explode(',', $this->settings['team']);
        }
        if ($name == 'auto_approve')
        {
            return explode(',', $this->settings['auto_approve']);
        }

        if ($name == 'office_ips')
        {
            return explode(',', $this->settings['office_ips']);
        }

        return $this->settings[$name];
    }

    public function getStartAndEndDateForMonth(&$rawCurrentMonth, &$rawCurrentYear, &$startDateData, &$endDateData, &$currentMonth, &$currentYear, $dontGetFuture = false, &$totalWorkingDayInMonth = 0, $preFillArrayData = null, $ignoreOffDay = false)
    {
        $rawCurrentMonth = intval($rawCurrentMonth);
        $rawCurrentYear  = intval($rawCurrentYear);

        if ($rawCurrentMonth <= 0 || $rawCurrentMonth > 12)
        {
            $currentMonth    = $rawCurrentMonth = date("n");
        }

        if ($rawCurrentYear < 2023)
        {
            $currentYear    = $rawCurrentYear = date("Y");
        }

        $startDate = $this->start_month_date;

        if ($startDate > 1)
        {
            $rawCurrentMonth--;

            if ($rawCurrentMonth <= 0)
            {
                $rawCurrentMonth = 12;
                $rawCurrentYear--;
            }
        }

        $currentMonth = intval($rawCurrentMonth);
        $currentYear  = intval($rawCurrentYear);

        $currentDate = $startDate;
        if ($currentMonth <= 0 || $currentMonth > 12)
        {
            $rawCurrentMonth = date("n");
            $currentMonth    = $rawCurrentMonth; // - (($startDate > 1) ? 0 : 1); #Get previous month incase startDate is not 1            
        }

        if ($currentYear < 2022)
        {
            $currentYear    = date("Y");
            $rawCurrentYear = $currentYear;
        }

        if ($currentMonth <= 0 || $rawCurrentMonth < 0)
        {
            $currentMonth = 12;
            $currentYear  -= 1;
        }

        $startDateData = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $currentDate);

        $allCheckin      = array();
        $currentDatetime = new \DateTime();
        $currentDatetime->setTime(0, 0, 0);

        for ($endDay = $startDate;;)
        {
            $days          = ($endDay < 10) ? "0$endDay" : $endDay;
            $currentMonths = ($currentMonth < 10) ? "0$currentMonth" : $currentMonth;
            $key           = "$days-$currentMonths-$currentYear";
            $currentDatetime->setDate($currentYear, $currentMonth, $endDay);

            if ($dontGetFuture && $currentDatetime->getTimestamp() > time())
            {
                break;
            }

            $isNotWorkingDay = AbsentRequestModel::isNotWorkingDay($currentDatetime);

            if (!$isNotWorkingDay)
            {
                $totalWorkingDayInMonth++;
            }


            if (!$ignoreOffDay || !$isNotWorkingDay)
            {

                if (!empty($preFillArrayData))
                {
                    if (isset($preFillArrayData['class']))
                    {
                        if ($isNotWorkingDay)
                        {
                            $preFillArrayData['class'] = self::DATE_STYLE_WEEKEND;
                        }
                        else
                        {
                            $preFillArrayData['class'] = self::DATE_STYLE_ABSENT;   //Default is absent
                        }
                    }

                    $preFillArrayData['date'] = $key;
                    $allCheckin[$key]         = $preFillArrayData;
                }
            }

            $currentDatetime->modify('+1 day');

            if ($currentDatetime->format('j') == $startDate)
            {
                break;
            }

            $endDay       = intval($currentDatetime->format('j'));
            $currentMonth = intval($currentDatetime->format('n'));
            $currentYear  = intval($currentDatetime->format('Y'));
        }


        $endDateData = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $endDay);

        return $allCheckin;
    }

}
