<?php

namespace App\Controllers;

use App\Models\SettingsModel;
use App\Models\UsersModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Settings extends BaseController
{
    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    private function requireSettingsPermission()
    {
        $currentUser = $this->users->findFirstById($this->session->userId);

        if (empty($currentUser) || (empty($currentUser['is_admin']) && empty($currentUser['is_it'])))
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function index()
    {
        $this->requireSettingsPermission();

        $settings = $this->settings->findByCanChange(1);

        foreach ($settings as &$s)
        {
            if (empty($s['descExt']))
            {
                $s['descExt'] = array();
            }
            else
            {
                $descExt      = json_decode($s['descExt']);
                $s['descExt'] = [];
                if (!empty($descExt))
                {
                    foreach ($descExt as $d)
                    {
                        $s['descExt'][] = ['descExtText' => $d];
                    }
                }
            }
        }

        $this->assign('settings', $settings);
        return $this->render();
    }

    public function update()
    {
        $this->requireSettingsPermission();

        $settings    = $this->settings->findByCanChange(1);
        $newSettings = $this->request->getPost();

        foreach ($settings as &$s)
        {
            if ($s['value'] != $newSettings[$s['key']])
            {
                $this->settings->update($s['key'], ['value' => $newSettings[$s['key']]]);
            }
        }
        
        return $this->showMessages("Thông báo", "Dữ liệu đã đượcc cập nhật thành công.", "/settings");
    }

    public function testmail($receiver)
    {
        $this->requireSettingsPermission();

        $content = $this->render("modules/email_test", false, false);

        $sendResult = $this->settings->email("Email thử nghiệm", $content, $receiver, false);

        if (!$sendResult)
        {
            return $this->showMessages("Thông báo", 'Lỗi khi gửi email, vui lòng thử lại sau hoặc liên hệ team system để báo lỗi.');
        }        
        else
        {
            return $this->showMessages("Thông báo", "Email đã được gửi thành công. Vui lòng kiểm tra hòm thư để nhận email.", "/settings");            
        }
        
    }
}
