<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class System extends Controller
{
    public function __construct()
    {
        if (! is_cli())
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    //php /opt/timesheet/public/index.php system index
    public function index()
    {
        $usersModel = new \App\Models\UsersModel();
        $requestModel = new \App\Models\AbsentRequestModel();
        $settingModel = new \App\Models\SettingsModel();
        
        $startDateData = "";
        $endDateData   = "";

        $this->settings->getStartAndEndDateForMonth($currentMonth, $currentYear, $startDateData, $endDateData, $currentMonth, $currentYear, false);
        
        $users = $usersModel->findAll();
        
        
        return "DONE\n";
    }

}
