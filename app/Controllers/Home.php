<?php

namespace App\Controllers;

class Home extends BaseController
{

    public function index()
    {
        if (!empty($this->session->userId))
        {
            return redirect()->to("/request");
        }
        
        $user = $this->users->authencationByRememberKey();
        
        if (empty($user))
        {
            return redirect()->to("/users");
        }
        
        return redirect()->to("/request");
    }

    public function holidays($action = 0, $date = "")
    {
        if (!$this->session->isAdmin)
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $holidays = new \App\Models\HolidaysModel();


        switch ($action)
        {
            case 1:
                $date = explode('-', $date);
                if (count($date) != 2)
                {
                    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
                }
                $holidays->del($date[0], $date[1]);
                return redirect()->to("/home/holidays");
            case 2:
                $date = explode('-', $date);
                if (count($date) != 3)
                {
                    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
                }
                $holidays->insert(['day' => $date[2], 'month' => $date[1]]);
                return redirect()->to("/home/holidays");
            case 0:
                break;
            default:
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->assign('holidays', $holidays->findAll());
        return $this->render();
    }
}
