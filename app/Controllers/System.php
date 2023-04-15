<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\SettingsModel;
use App\Models\UsersModel;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class System extends Controller
{

    public function __construct()
    {
//        if (! is_cli())
//        {
//            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
//        }
        $this->settings = new SettingsModel();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    //php /opt/timesheet/public/index.php system index
    public function index()
    {
        $usersModel   = new \App\Models\UsersModel();
        $requestModel = new \App\Models\AbsentRequestModel();
        $settingModel = new \App\Models\SettingsModel();

        $startDateData = "";
        $endDateData   = "";

        $this->settings->getStartAndEndDateForMonth($currentMonth, $currentYear, $startDateData, $endDateData, $currentMonth, $currentYear, false);

        $this->settings->start_month_date = 16;
        
        $Datetime = new \DateTime();
        //$Datetime->setTime(0, 0, 0);
        $Datetime->setDate($currentYear, $currentMonth, $this->settings->start_month_date);
        $Datetime->modify('-1 day');
        
        $checkDate = $Datetime->format('Y-m-d');
        
        if ($checkDate <= $this->settings->calculated_month_report)
        {
            return "Already did";
        }
        
        $currentDate = date("Y-m-d");
        
        if ($currentDate < $checkDate)
        {
            return "Too early";
        }
        
        if (intval($Datetime->format('H') < 23))
        {
            return "Going to be created";
        }

        $users = $usersModel->findAll();

        return $Datetime->format('H');
    }

}
