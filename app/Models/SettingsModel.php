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
    public const DATE_STYLE_NOT_ENOUGH_TIME = 'table-warning not-enough';

    protected $table            = 'settings';
    protected $primaryKey       = 'key';
    protected $useAutoIncrement = false;
    // protected $returnType         = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['value'];
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

        $data = $this->findAll();

        foreach ($data as $d)
        {
            $this->settings[$d['key']] = $d['value'];
        }
    }

    public function email($subject, $content, $to, $toHR = true, $file = false)
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
        if ($toHR)
        {
            $email->setCC($this->email_HR);
        }

        $email->setSubject($subject);
        $email->setMessage($content);

        if (!empty($file) && file_exists($file))
            $email->attach($file);

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
