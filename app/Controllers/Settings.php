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
    private const STAFF_SETTING_KEYS = [
        'team',
        'work_time_finish',
        'work_time_start',
        'latest_work_time_start',
        'email_HR',
        'break_time_finish',
        'break_time_start',
        'consider_late_if_not_enough_8_hours',
    ];

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        if (!empty($this->session->isAdmin))
        {
            return redirect()->to("/");
        }
    }

    private function requireStaffSettingsPermission()
    {
        $currentUser = $this->users->findFirstById($this->session->userId);

        if (empty($currentUser) || (empty($currentUser['is_admin']) && empty($currentUser['is_it'])))
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function staff()
    {
        $this->requireStaffSettingsPermission();

        $rows = $this->settings
                ->whereIn('key', self::STAFF_SETTING_KEYS)
                ->findAll();

        $values = [];
        foreach ($rows as $row)
        {
            $values[$row['key']] = $row['value'];
        }

        foreach (self::STAFF_SETTING_KEYS as $key)
        {
            if (!array_key_exists($key, $values))
            {
                return $this->showMessages(
                    'Thiếu cấu hình',
                    'Không tìm thấy cấu hình ' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . ' trong database.'
                );
            }
        }

        $teams = [];
        foreach (explode(',', $values['team']) as $team)
        {
            $team = trim($team);
            if ($team !== '')
            {
                $teams[] = [
                    'teamName' => htmlspecialchars($team, ENT_QUOTES, 'UTF-8')
                ];
            }
        }

        $this->assign('teamSettings', $teams);
        $this->assign('work_time_finish', htmlspecialchars($values['work_time_finish'], ENT_QUOTES, 'UTF-8'));
        $this->assign('work_time_start', htmlspecialchars($values['work_time_start'], ENT_QUOTES, 'UTF-8'));
        $this->assign('latest_work_time_start', htmlspecialchars($values['latest_work_time_start'], ENT_QUOTES, 'UTF-8'));
        $this->assign('email_HR', htmlspecialchars($values['email_HR'], ENT_QUOTES, 'UTF-8'));
        $this->assign('break_time_finish', htmlspecialchars($values['break_time_finish'], ENT_QUOTES, 'UTF-8'));
        $this->assign('break_time_start', htmlspecialchars($values['break_time_start'], ENT_QUOTES, 'UTF-8'));
        $this->assign(
            'consider_late_checked',
            !empty($values['consider_late_if_not_enough_8_hours']) ? 'checked' : ''
        );

        return $this->render();
    }

    public function staffupdate()
    {
        $this->requireStaffSettingsPermission();

        $teams = $this->request->getPost('team');
        if (!is_array($teams))
        {
            return $this->showMessages('Dữ liệu không hợp lệ', 'Danh sách phòng ban không hợp lệ.');
        }

        $normalizedTeams = [];
        foreach ($teams as $team)
        {
            $team = trim((string) $team);

            if ($team === '')
            {
                continue;
            }

            if (mb_strlen($team) > 45 || strpos($team, ',') !== false)
            {
                return $this->showMessages(
                    'Dữ liệu không hợp lệ',
                    'Tên phòng ban tối đa 45 ký tự và không được chứa dấu phẩy.'
                );
            }

            if (!in_array($team, $normalizedTeams, true))
            {
                $normalizedTeams[] = $team;
            }
        }

        if (empty($normalizedTeams))
        {
            return $this->showMessages('Dữ liệu không hợp lệ', 'Phải có ít nhất một phòng ban.');
        }

        $timeKeys = [
            'work_time_finish',
            'work_time_start',
            'latest_work_time_start',
        ];

        $newValues = [
            'team' => implode(',', $normalizedTeams),
        ];

        foreach ($timeKeys as $key)
        {
            $value = trim((string) $this->request->getPost($key));
            if (!preg_match('/^(?:[01]\\d|2[0-3]):[0-5]\\d$/', $value))
            {
                return $this->showMessages(
                    'Dữ liệu không hợp lệ',
                    'Các mốc giờ làm việc phải có định dạng HH:MM.'
                );
            }
            $newValues[$key] = $value;
        }

        foreach (['break_time_start', 'break_time_finish'] as $key)
        {
            $value = trim((string) $this->request->getPost($key));

            if (!preg_match('/^(?:[0-9]|1[0-9]|2[0-3])$/', $value))
            {
                return $this->showMessages(
                    'Dữ liệu không hợp lệ',
                    'Giờ nghỉ trưa phải là số nguyên từ 0 đến 23.'
                );
            }

            $newValues[$key] = (string) intval($value);
        }

        if (intval($newValues['break_time_finish']) <= intval($newValues['break_time_start']))
        {
            return $this->showMessages(
                'Dữ liệu không hợp lệ',
                'Giờ kết thúc nghỉ trưa phải lớn hơn giờ bắt đầu nghỉ trưa.'
            );
        }

        $emailValue = trim((string) $this->request->getPost('email_HR'));
        $emails     = [];

        foreach (explode(',', $emailValue) as $email)
        {
            $email = trim($email);

            if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false)
            {
                return $this->showMessages(
                    'Dữ liệu không hợp lệ',
                    'Email HR không hợp lệ. Nếu có nhiều email, hãy phân cách bằng dấu phẩy.'
                );
            }

            if (!in_array($email, $emails, true))
            {
                $emails[] = $email;
            }
        }

        $newValues['email_HR'] = implode(',', $emails);
        $newValues['consider_late_if_not_enough_8_hours'] =
            $this->request->getPost('consider_late_if_not_enough_8_hours') ? '1' : '0';

        foreach (self::STAFF_SETTING_KEYS as $key)
        {
            $this->settings->update($key, ['value' => $newValues[$key]]);
        }

        return $this->showMessages(
            'Cập nhật thành công',
            'Các thiết lập chấm công đã được cập nhật.',
            site_url('/settings/staff')
        );
    }

    public function index()
    {
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
