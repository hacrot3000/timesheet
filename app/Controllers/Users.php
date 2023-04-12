<?php

namespace App\Controllers;

use App\Models\AbsentRequestModel;
use App\Models\CheckinModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @property CheckinModel $checkin
 */
class Users extends BaseController
{

    private const DATE_STYLE_NORMAL    = 'table-active';
    private const DATE_STYLE_WEEKEND   = 'table-dark';
    private const DATE_STYLE_ABSENT    = 'table-danger';
    private const DATE_STYLE_COME_LATE = 'table-warning';

    /**
     * @var CheckinModel
     */
    private $checkin;

    /**
     * @var AbsentRequestModel
     */
    private $absentRequest;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->checkin       = new CheckinModel();
        $this->absentRequest = new AbsentRequestModel();

        #$this->assignAppend('site_title', ' - Danh sách nhân viên');
    }

    public function Index()
    {
        $month       = date("n");
        $yearQuarter = ceil($month / 3);

        if (($yearQuarter) == 1)
        {
            $this->assign('is_first_quater', array(array()));
        }
        else
        {
            $this->assign('is_first_quater', array());
        }

        $listUser = $this->users->getAll();

        $this->assign("listUser", $listUser);
        return $this->render();
    }

    public function updateanualleave($type, $user_id, $value)
    {
        if (!$this->session->isAdmin)
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }


        $key  = ($type == 1) ? "paid_leave_left_this_year" : "paid_leave_per_year";
        $data = [
            $key => intval($value)
        ];

        $this->users->update(intval($user_id), $data);

        return redirect()->to("/users");
    }

    public function resetpass($user_id)
    {
        if (!$this->session->isAdmin)
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $user = $this->users->findFirstById($user_id);

        if (!empty($user['is_admin']))
        {
            $messages = 'Bạn không thể xoá mật khẩu một quản trị viên. Hãy liên hệ nhân viên hệ thống để được hướng dẫn cách thực hiện.';
            $subject  = 'Thao tác không hợp lệ';
            return $this->showMessages($subject, $messages);
        }

        $this->users->update(intval($user_id), ['password' => '']);

        return redirect()->to("/users");
    }

    public function delacc($user_id)
    {
        $user_id = intval($user_id);

        if (!$this->session->isAdmin)
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $user = $this->users->findFirstById($user_id);

        if (!empty($user['is_admin']))
        {
            $messages = 'Bạn không thể xoá tài khoản của quản trị viên. Hãy liên hệ nhân viên hệ thống để được hướng dẫn cách thực hiện.';
            $subject  = 'Thao tác không hợp lệ';
            return $this->showMessages($subject, $messages);
        }

        $this->users->update($user_id, ['username' => $user['username'] . "-del"]);

        $this->users->delete($user_id);

        return redirect()->to("/users");
    }

    public function profile()
    {
        $userId = $this->session->userId;

        if (empty($userId))
        {
            return redirect()->to("/users/login?ret=/users/profile");
        }

        $user = $this->users->findFirstById($userId);

        if (empty($user))
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $teams    = $this->settings->team;
        $teamList = array();

        foreach ($teams as &$t)
        {
            $teamList[] = [
                'name'     => $t,
                'selected' => ($t == $user['team']) ? 'selected' : ''
            ];
        }

        $this->assign('teams', $teamList);
        $this->assign($user);

        return $this->render();
    }

    public function profileupdate()
    {
        $userId = $this->session->userId;

        if (empty($userId))
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $user = $this->users->findFirstById($userId);

        if (empty($user))
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data    = [
            'fullname' => $this->request->getPost('fullname'),
            'team'     => $this->request->getPost('team'),
            'email'    => $this->request->getPost('email'),
        ];
        $pass    = $this->request->getPost('password');
        $errMess = "";

        if (!empty($pass))
        {
            $pass = $this->users->encryptPassword($userId, $pass, $errMess);

            if (!empty($errMess))
            {
                return $this->showMessages("Mật khẩu không an toàn", $errMess);
            }

            $data['password'] = $pass;
        }

        $this->users->update($userId, $data);

        return $this->showMessages("Cập nhật thành công", "Thông tin mới đã được ghi nhận.", site_url('/users/profile'));
    }

    private function doAuth($ret)
    {
        if (empty($ret))
        {
            $ret = site_url();
        }
        return redirect()->to($ret);
    }

    public function login()
    {
        $ret = $this->request->getGetPost('ret');

        $data = $this->users->authencationByRememberKey();
        if (!empty($data))
        {
            return $this->doAuth($ret);
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (!empty($username))
        {
            $remember = $this->request->getPost('remember');

            $data = $this->users->authencation($username, $password, $remember);

            if (empty($data))
            {
                return $this->showMessages("", "Tên đăng nhập hoặt mật khẩu không chính xác");
            }

            if (empty($data['password']))
            {
                return $this->showMessages("Vui lòng thiết lập mật khẩu",
                                [
                                    "Để tạp sự thuận tiện, mật khẩu không bắt buộc phải có, tuy nhiên vì lý do an toàn tốt nhất bạn nên thiết lập mật khẩu cho riêng mình.",
                                    "Thông báo này sẽ tiếp tục xuất hiện mỗi lần bạn đăng nhập cho đến khi một mật khẩu được thiết lập.",
                                ],
                                '/users/profile');
            }

            return $this->doAuth($ret);
        }

        $this->assign('ret', $ret);

        return $this->render('', false, true);
    }

    public function logout()
    {
        $this->users->logout();
        return $this->doAuth('');
    }

    public function checklogin($step, $authKey = "", $apiKey = "", $ip = "")
    {
        $retData = ['auth' => 'N', 'key' => ''];

        if (empty($this->session->userId))
        {
            $this->users->authencationByRememberKey();
        }

        if (empty($this->session->userId))
        {
            return json_encode($retData);
        }

        //Check is user already has a checkin today
        if ($this->checkin->isCheckedInToDay($this->session->userId, $this->settings->break_time_start))
        {
            return json_encode($retData);
        }

        $c = $this->absentRequest->findByUser_id($this->session->userId, date('Y-m-d'), date('Y-m-d'));
        if (!empty($c))
        {
            return json_encode($retData);
        }

        $iplogs = new \App\Models\IplogsModel();
        switch ($step)
        {
            case 0:

                $id = $iplogs->insert($this->session->userId);

                $retData = ['key' => 'bdc_00ae1d5b22744611be37e7d5b65fcb9c', 'auth' => $id, 'url' => 'https://api.bigdatacloud.net/data/ip-geolocation'];

                break;

            case 1:

                if (empty($authKey) || empty($apiKey) || empty($ip))
                {
                    break;
                }

                $auth = $iplogs->findFirstById($authKey);

                if (empty($auth) || $auth['step'] != 1 || $auth['valid_until'] < time())
                {
                    break;
                }

                if (!$this->settings->isOfficeIp($ip))
                {
                    break;
                }

                $auth['ip'] = $ip;
                $iplogs->update($auth);

                $retData['auth'] = 'Y';

                break;
            case 2:
                $auth = $iplogs->findFirstByUser_id($this->session->userId);

                $mess = [
                    "Có thể việc checkin đã được ghi nhận bởi hệ thống trước khi bạn thực hiện.",
                    "Có thể liên kết bạn dùng đã hết hạn, hãy thử lại lần nữa nếu vẫn chưa thấy lượt checkin.",
                    "Hãy kiểm tra và chắc chắn bạn đã đăng nhập đúng tài khoản của mình.",
                ];

                if (empty($auth) || $auth['step'] != 2 || $auth['valid_until'] < time())
                {
                    return $this->showMessages("Lỗi thao tác", $mess);
                }

                if (!$this->settings->isOfficeIp($auth['ip']))
                {
                    return $this->showMessages("Lỗi thao tác", $mess);
                }

                $data = array(
                    'user_id'          => $this->session->userId,
                    'request_date'     => date('Y-m-d'),
                    'absent_type'      => AbsentRequestModel::ABSENT_TYPE_FORGOT_CHECKIN,
                    'approve_status'   => AbsentRequestModel::APPROVE_STATUS_APPROVED,
                    'leave_count_type' => AbsentRequestModel::LEAVE_COUNT_TYPE_IGNORE,
                    'request_group'    => -$this->session->userId
                );

                $this->absentRequest->insert($data);

                return redirect()->to("/request");
        }

        return json_encode($retData);
    }

    public function download($month = 0, $year = 0)
    {


        if (!$this->session->isAdmin)
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (empty($month) || empty($year))
        {

            $this->genMonthDate(date("n"), date("Y"), date("Y"));

            return $this->render();
        }

        $startDateData          = "";
        $endDateData            = "";
        $totalWorkingDayInMonth = 0;

        $preFillArrayData = array(
            'users' => [],
        );

        $report = $this->settings->getStartAndEndDateForMonth($month, $year, $startDateData, $endDateData, $month, $year, false, $totalWorkingDayInMonth, $preFillArrayData, true);

        $allCheckinraw    = $this->checkin->getByMonth($startDateData, $endDateData);
        $allCheckinbyUser = array();

        foreach ($allCheckinraw as &$checkin)
        {
            if ($checkin['firstCheckPoint'] == $checkin['lastCheckPoint'])
            {
                $checkin['lastCheckPoint'] = '';
            }
            if (!isset($allCheckinbyUser[$checkin['user_id']]))
            {
                $allCheckinbyUser[$checkin['user_id']] = [
                    'fullname'         => $checkin['fullname'],
                    'totalPayLeave'    => 0,
                    'totalLateRequest' => 0,
                ];
            }
            $allCheckinbyUser[$checkin['user_id']][$checkin['date']] = [
                'firstCheckPoint' => $checkin['firstCheckPoint'],
                'lastCheckPoint'  => $checkin['lastCheckPoint'],
            ];
        }

        $allRequest = $this->absentRequest->findByMonth($startDateData, $endDateData, false, true);

        //Fill exception checkin base on approved request
        foreach ($allRequest as &$request)
        {
            $date   = $request['request_date'];
            $userId = $request['user_id'];

            //User not in checkint list
            if (!isset($allCheckinbyUser[$userId]))
            {
                continue;
            }
            if ($request['leave_count_type'] == AbsentRequestModel::LEAVE_COUNT_TYPE_PAID_LEAVE && !isset($allCheckinbyUser[$userId][$date]))
            {
                $allCheckinbyUser[$userId]['totalPayLeave']++;
            }

            switch ($request['absent_type'])
            {
                case AbsentRequestModel::ABSENT_TYPE_COME_LATE:
                    //Chỉ trừ phép đi trễ nếu này đó checkin trễ, nếu vẫn checkin sớm thì không tính phép
                    if ($allCheckinbyUser[$userId][$date]['firstCheckPoint'] > $this->settings->latest_work_time_start)
                    {
                        $allCheckinbyUser[$userId]['totalLateRequest']++;
                    }
                case AbsentRequestModel::ABSENT_TYPE_FORGOT_CHECKIN:
                    if (!isset($allCheckinbyUser[$userId][$date]))
                    {
                        $allCheckinbyUser[$userId][$date] = [
                            'firstCheckPoint' => $this->settings->work_time_start,
                            'lastCheckPoint'  => '',
                        ];
                    }
                    else
                    {
                        $allCheckinbyUser[$userId][$date]['firstCheckPoint'] = $this->settings->work_time_start;
                    }
                    break;
                case AbsentRequestModel::ABSENT_TYPE_FORGOT_CHECKOUT:
                case AbsentRequestModel::ABSENT_TYPE_LEAVE_EARLY:
                    if (!isset($allCheckinbyUser[$userId][$date]))
                    {
                        $allCheckinbyUser[$userId][$date] = [
                            'firstCheckPoint' => '',
                            'lastCheckPoint'  => $this->settings->work_time_finish,
                        ];
                    }
                    else
                    {
                        $allCheckinbyUser[$userId][$date]['lastCheckPoint'] = $this->settings->work_time_finish;
                    }
                    break;
                case AbsentRequestModel::ABSENT_TYPE_WHOLE_DAY:
                case AbsentRequestModel::ABSENT_TYPE_WORK_AT_HOME:
                    if (!isset($allCheckinbyUser[$userId][$date]))
                    {
                        $allCheckinbyUser[$userId][$date] = [
                            'firstCheckPoint' => $this->settings->work_time_start,
                            'lastCheckPoint'  => $this->settings->work_time_finish,
                        ];
                    }
                    else
                    {
                        $allCheckinbyUser[$userId][$date]['firstCheckPoint'] = $this->settings->work_time_start;
                        $allCheckinbyUser[$userId][$date]['lastCheckPoint']  = $this->settings->work_time_finish;
                    }
                    break;
            }
        }


        //Fill the checkin record into report
        foreach ($allCheckinbyUser as $userId => &$checkin)
        {
            $checkin['totalLate'] = 0;
            $workedDate           = 0;

            foreach ($checkin as $date => &$checktime)
            {
                if (!isset($report[$date]))
                {
                    continue;
                }

                $workedDate++;

                $report[$date]['users'][$userId] = [$checktime['firstCheckPoint'], $checktime['lastCheckPoint']];

                if ($checktime['firstCheckPoint'] > $this->settings->latest_work_time_start)
                {
                    $checkin['totalLate']++;
                }
            }
            $checkin['totalAbsent'] = $totalWorkingDayInMonth - $workedDate;
        }

        $filepath = WRITEPATH . 'cache/report_template.xlsx';

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filepath);

        $reportSheet  = $spreadsheet->getSheet(0);
        $checkinSheet = $spreadsheet->getSheet(1);

        $rowIndex = 4;
        foreach ($allCheckinbyUser as &$user)
        {
            $reportSheet->setCellValue("A$rowIndex", $user['fullname']);
            $checkinSheet->setCellValue("A$rowIndex", $user['fullname']);
            $user['rowindex'] = $rowIndex;
            $rowIndex++;
        }

        $dateColIndex = array();

        $colIndex = 6;
        foreach ($report as $date => &$users)
        {
            $dateColIndex[$date] = $colIndex;

            $reportSheet->setCellValue([$colIndex, 2], $date);
            $reportSheet->setCellValue([$colIndex, 3], "Vào");
            $reportSheet->setCellValue([$colIndex + 1, 3], "Ra");

            $checkinSheet->setCellValue([$colIndex - 4, 2], $date);
            $checkinSheet->setCellValue([$colIndex - 4, 3], "Vào");
            $checkinSheet->setCellValue([$colIndex - 3, 3], "Ra");

            foreach ($users['users'] as $userId => &$times)
            {
                $userRow = $allCheckinbyUser[$userId]['rowindex'];

                $reportSheet->setCellValue([$colIndex, $userRow], $times[0]);
                $reportSheet->setCellValue([$colIndex + 1, $userRow], $times[1]);

                if ($allCheckinbyUser[$userId]['totalLate'] > 0)
                {
                    $reportSheet->setCellValue("C$userRow", $allCheckinbyUser[$userId]['totalLate']);
                }

                if ($allCheckinbyUser[$userId]['totalAbsent'] > 0)
                {
                    $reportSheet->setCellValue("B$userRow", $allCheckinbyUser[$userId]['totalAbsent']);
                }

                if ($allCheckinbyUser[$userId]['totalPayLeave'] > 0)
                {
                    $reportSheet->setCellValue("D$userRow", $allCheckinbyUser[$userId]['totalPayLeave']);
                }

                if ($allCheckinbyUser[$userId]['totalLateRequest'] > 0)
                {
                    $reportSheet->setCellValue("E$userRow", $allCheckinbyUser[$userId]['totalLateRequest']);
                }
                
            }


            $colIndex += 2;
        }

        foreach ($allCheckinraw as &$checkin)
        {
            if (!isset($dateColIndex[$checkin['date']]))
            {
                continue;
            }
            $userRow = $allCheckinbyUser[$checkin['user_id']]['rowindex'];
            $userCol = $dateColIndex[$checkin['date']];

            $checkinSheet->setCellValue([$userCol - 4, $userRow], $checkin['firstCheckPoint']);
            $checkinSheet->setCellValue([$userCol - 3, $userRow], $checkin['lastCheckPoint']);
        }

        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode("timesheetreport.xlsx") . '"');
        $writer->save('php://output');
        exit;
    }
}
