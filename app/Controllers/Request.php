<?php

namespace App\Controllers;

use App\Models\AbsentRequestModel;
use App\Models\CheckinModel;

/**
 * @property CheckinModel $checkin
 */
class Request extends BaseController
{

    protected $helpers = ['form'];

    /**
     * @var CheckinModel
     */
    private $checkin;

    /**
     * @var \Config\Validation
     */
    private $validation;

    /**
     * @var AbsentRequestModel
     */
    private $absentRequest;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->checkin       = new CheckinModel();
        $this->absentRequest = new AbsentRequestModel();

        $this->validation = \Config\Services::validation();

        #$this->assignAppend('site_title', ' - Danh sách nhân viên');
    }

    public function create($userId = 0)
    {
        $userId = intval($userId);

        if ($this->session->isAdmin)
        {
            $allUsers = $this->users->findAll();
        }
        else
        {
            $userId = $this->session->userId;

            if (empty($userId))
            {
                return redirect()->to("/users/login?ret=/request/create");
            }
            $allUsers = $this->users->findById($userId);
        }

        foreach ($allUsers as &$u)
        {
            if ($u['id'] == $userId)
            {
                $u['selected'] = 'selected';
            }
            else
            {
                $u['selected'] = '';
            }
        }

        $this->assign('user_id', $userId);
        $this->assign('allUsers', $allUsers);

        return $this->render();
    }

    public function submit()
    {
        $userId = intval($this->request->getPost('userid'));

        if (!$this->session->isAdmin)
        {
            if (empty($userId) || $userId != $this->session->userId)
            {
                $result = ['errors' => 'Bạn cần đăng nhập đúng tài khoản gửi yêu cầu.'];
                return;
            }
        }

        $requestGroupId = sprintf("%u", crc32(uniqid() . time()));

        $requestType   = intval($this->request->getPost('requestType'));
        $isAutoApprove = $this->settings->isAutoApprove($requestType);
        $startDate     = $this->request->getPost('startDate');

        $data = array(
            'user_id'          => $userId,
            'request_date'     => $startDate,
            'absent_type'      => $requestType,
            'approve_status'   => $isAutoApprove ? AbsentRequestModel::APPROVE_STATUS_APPROVED : AbsentRequestModel::APPROVE_STATUS_WAITING,
            'leave_count_type' => $isAutoApprove,
            'request_group'    => $requestGroupId,
        );

        $finishDateRaw = $finishDate    = trim($this->request->getPost('finishDate'));

        $this->validation->reset();
        $this->validation->setRuleGroup('request');

        if (!$this->validation->run($data))
        {
            $result = ['errors' => implode("\n", $this->validation->getErrors())];
            return json_encode($result);
        }

        if (!empty($finishDate))
        {
            if ($finishDate < $data['request_date'])
            {
                $result = ['errors' => 'Ngày kết thúc phải sau ngày bắt đầu.'];
                return json_encode($result);
            }
        }
        else
        {
            $finishDate = $data['request_date'];
        }

//        if ($data['absent_type'] != AbsentRequestModel::ABSENT_TYPE_FORGOT_CHECKIN)
//        {
//            $validationRule = [
//                'document' => [
//                    'rules'  => [
//                        'uploaded[document]',
//                        'is_image[document]',
//                        'mime_in[document,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
//                        'max_size[document,102400]',
//                        'max_dims[document,2048,1080]',
//                    ],
//                    'errors' => [
//                        'uploaded' => 'Vui lòng chọn hình ảnh xác nhận.',
//                        'is_image' => 'Vui lòng chọn file hình định dạng png hoặc jpeg.',
//                        'mime_in'  => 'Vui lòng chọn file hình định dạng png hoặc jpeg.',
//                        'max_size' => 'Dung lượng file quá lớn.',
//                        'max_dims' => 'Kích thước file quá lớn.',
//                    ],
//                ],
//            ];
//
//            if (!$this->validate($validationRule))
//            {
//                $result = ['errors' => implode(',', $this->validator->getErrors())];
//
//                return json_encode($result);
//            }
//
//            $img = $this->request->getFile('document');
//
//            if ($img->hasMoved())
//            {
//                $result = ['errors' => 'The file has already been moved.'];
//
//                return json_encode($result);
//            }
//
//            $filepath = WRITEPATH . 'uploads/' . $img->store('', $requestGroupId . "." . $img->getExtension());
//
//            \Config\Services::image('gd')
//                    ->withFile($filepath)
//                    ->resize(800, 800, true, 'auto')
//                    ->save($filepath);
//        }

        $allData = array($data);
        $run     = new \DateTime($data['request_date']);

        while ($data['request_date'] < $finishDate)
        {
            $run->modify('+1 day');
            if ($requestType != AbsentRequestModel::ABSENT_TYPE_ADD_PAID_LEAVE && $this->absentRequest->isNotWorkingDay($run))
            {
                continue;
            }
            $data['request_date'] = $run->format('Y-m-d');
            $allData[]            = $data;
        }


        $user            = $this->users->findFirstById($userId);
        $leademail       = $this->users->leaderEmail($user['team']);
        $requestTypeName = AbsentRequestModel::ABSENT_TYPE_NAME[$requestType];

        if (!empty($finishDateRaw))
        {
            if (
                    $requestType == AbsentRequestModel::ABSENT_TYPE_WHOLE_DAY ||
                    $requestType == AbsentRequestModel::ABSENT_TYPE_ADD_PAID_LEAVE ||
                    $requestType == AbsentRequestModel::ABSENT_TYPE_WORK_AT_HOME
                    )
            {
                $date = "từ ngày $startDate đến hết ngày $finishDateRaw";
            }
            else
            {
                $date = "từ ngày $startDate đến ngày $finishDateRaw";
            }
        }
        else
        {
            $date = $startDate;
        }

        if (!empty($user['email']))
        {
            $leademail .= ",{$user['email']}";
        }

        $this->assign($user);
        $this->assign('requesttype', $requestTypeName);
        $this->assign('requestDate', $date);
        $content = $this->render("modules/email_request", false, false);

        $sendResult = $this->settings->email("{$user['fullname']} đăng ký " . $requestTypeName, $content, $leademail);

        if (!$sendResult)
        {
            $result = ['errors' => 'Lỗi khi gửi email cho HR và leader, vui lòng thử lại sau hoặc liên hệ team system để báo lỗi.'];
            return json_encode($result);
        }

        $this->absentRequest->upsertBatch($allData);

        $result = ['errors' => ''];
        return json_encode($result);
    }

    public function Index($userId = 0, $rawCurrentMonth = 0, $rawCurrentYear = 0)
    {
        if (empty($this->session->userId))
        {
            return redirect()->to("/users/login?ret=/request/index/$userId/$rawCurrentMonth/$rawCurrentYear");
        }

        if (empty($userId))
        {
            $userId = $this->session->userId;
        }

        //Calculate user info.
        $userInfo = $this->users->findFirstById($userId);

        if ($this->session->userId != $userId)
        {
            if ($this->session->isAdmin)
            {
                //Do nothing, admin can view all
            }
            else if ($this->session->isLeader)
            {
                if ($this->session->team != $userInfo['team'])//Leader can view for his/her team only
                {
                    return redirect()->to("/request");
                }
            }
            else
            {
                return redirect()->to("/request");
            }
        }

        if (empty($userInfo))
        {

            return redirect()->to("/users");
        }

        //Calculate timesheet

        $currentMonth             = 0;
        $currentYear              = 0;
        $maxCol                   = 2;
        $totalDayComeLate         = 0;
        $totalDayAbsent           = 0;
        $totalDayComeLateApproved = 0;
        $totalDayAbsentApproved   = 0;
        $totalWorkingDayInMonth   = 0;
        //
        $startDate                = $this->settings->start_month_date;
        $currentDate              = $startDate;
        $endDateData              = "";
        $startDateData            = "";
        if (empty($currentMonth))
        {
            $currentMonth = date("n");
            $currentYear  = date("Y");
        }

        $preFillArrayData = array(
            'date'                => "",
            'checkPoint'          => '',
            'class'               => '',
            'workHours'           => '',
            'approved_request'    => array(),
            'pending_request'     => array(),
            'rejected_request'    => array(),
            'anualyleave_request' => array(),
            'anualyadd_request'   => array(),
        );

        $allCheckin = $this->settings->getStartAndEndDateForMonth($rawCurrentMonth, $rawCurrentYear, $startDateData, $endDateData, $currentMonth, $currentYear, true, $totalWorkingDayInMonth, $preFillArrayData);
        $this->genMonthDate($currentMonth, $currentYear);

        $allCheckinRaw = $this->checkin->getByUser($userId, $startDateData, $endDateData);

        foreach ($allCheckinRaw as &$check)
        {
            if (intval($check['numberOfCheckPoint']) > $maxCol)
                $maxCol = $check['numberOfCheckPoint'];

            unset($check['numberOfCheckPoint']);

            if (isset($allCheckin[$check['date']]))
            {
                $allCheckin[$check['date']]['checkPoint'] = $check['checkPoint'];
            }
        }

        foreach ($allCheckin as &$check)
        {
            if (empty($check['checkPoint']))
            {
                $check['checkPoint'] = array_fill(0, $maxCol, array('time' => ''));
                // if ($check['class'] == self::DATE_STYLE_ABSENT)
                //     $totalDayAbsent++;
                continue;
            }

            $checkPoints = explode(',', $check['checkPoint']);

            $check['checkPoint'] = array();

            foreach ($checkPoints as $p)
            {
                $check['checkPoint'][] = array('time' => $p);
            }

            for ($i = count($check['checkPoint']); $i < $maxCol; $i++)
            {
                $check['checkPoint'][] = array('time' => '');
            }

            if ($check['class'] == \App\Models\SettingsModel::DATE_STYLE_WEEKEND)
            {
                continue;
            }

            if (count($checkPoints) > 1)
            {//At least 2 checkin and checkout
                $firstCheckIn = $checkPoints[0];
                $lastCheckIn  = $checkPoints[count($checkPoints) - 1];

                $from_time = strtotime("$currentYear-$currentMonth-$currentDate $firstCheckIn:00");
                $to_time   = strtotime("$currentYear-$currentMonth-$currentDate $lastCheckIn:00");

                $check['workHours'] = round(abs($to_time - $from_time) / 3600, 1);

                if (intval($firstCheckIn) < $this->settings->break_time_start && intval($lastCheckIn) > $this->settings->break_time_finish)
                    $check['workHours'] -= min(1.5, $this->settings->break_time_finish - $this->settings->break_time_start);

                if ($firstCheckIn > $this->settings->latest_work_time_start)
                {
                    $check['class'] = \App\Models\SettingsModel::DATE_STYLE_COME_LATE;
                    //$totalDayComeLate++;
                }
                else
                {

//                    if ($check['workHours'] < 8 && $this->settings->consider_late_if_not_enough_8_hours)
//                    {
//                        $check['class'] = \App\Models\SettingsModel::DATE_STYLE_NOT_ENOUGH_TIME;
//                    }
//                    else
                    if ($check['class'] == \App\Models\SettingsModel::DATE_STYLE_ABSENT)
                    {
                        $check['class'] = \App\Models\SettingsModel::DATE_STYLE_NORMAL;
                    }
                }
            }
            else
            {
                $check['class'] = \App\Models\SettingsModel::DATE_STYLE_ABSENT;
                // $totalDayAbsent++;
            }
        }

        $headers = array();

        for ($i = 1; $i <= $maxCol; $i++)
        {
            $headers[] = array("col" => $i);
        }

        //Calculate absent request info

        $requests = $this->absentRequest->findByUser_id($userId, $startDateData, $endDateData);

        foreach ($requests as &$request)
        {
            if (isset($allCheckin[$request['request_date']]))
            {
                $turnOnStatus = "";

                if ($request['approve_status'] == AbsentRequestModel::APPROVE_STATUS_WAITING)
                {
                    $turnOnStatus = 'pending_request';
                }
                elseif ($request['approve_status'] == AbsentRequestModel::APPROVE_STATUS_REJECTED)
                {
                    $turnOnStatus = 'rejected_request';
                }
                else
                {
                    $class               = $allCheckin[$request['request_date']]['class'];
                    $checkpoint          = $allCheckin[$request['request_date']]['checkPoint'];
                    $lastCheckpointIndex = count($checkpoint) - 1;

                    switch ($request['absent_type'])
                    {
                        case AbsentRequestModel::ABSENT_TYPE_FORGOT_CHECKIN:

                            if (!empty($checkpoint[0]['time']) && empty($checkpoint[1]['time']))
                            {
                                $allCheckin[$request['request_date']]['checkPoint'][1]['time'] = $allCheckin[$request['request_date']]['checkPoint'][0]['time'];
                            }
                            $class                                                         = \App\Models\SettingsModel::DATE_STYLE_COME_LATE;
                            $allCheckin[$request['request_date']]['checkPoint'][0]['time'] = $this->settings->work_time_start;
                            $turnOnStatus                                                  = 'approved_request';
                            if ($checkpoint[$lastCheckpointIndex]['time'] >= $this->settings->work_time_finish)
                            {
                                $class = \App\Models\SettingsModel::DATE_STYLE_NORMAL;
                            }
                            break;
                        case AbsentRequestModel::ABSENT_TYPE_FORGOT_CHECKOUT:
                            $class                                                         = \App\Models\SettingsModel::DATE_STYLE_COME_LATE;
                            $allCheckin[$request['request_date']]['checkPoint'][1]['time'] = $this->settings->work_time_finish;
                            $turnOnStatus                                                  = 'approved_request';
                            if (!empty($checkpoint[0]['time']) && $checkpoint[0]['time'] <= $this->settings->work_time_start)
                            {
                                $class = \App\Models\SettingsModel::DATE_STYLE_NORMAL;
                            }
                            break;
                        case AbsentRequestModel::ABSENT_TYPE_WORK_AT_HOME:
                            $class        = \App\Models\SettingsModel::DATE_STYLE_NORMAL;
                            $turnOnStatus = 'approved_request';
                            break;
                        case AbsentRequestModel::ABSENT_TYPE_COME_LATE:
                        case AbsentRequestModel::ABSENT_TYPE_WHOLE_DAY:
                        case AbsentRequestModel::ABSENT_TYPE_LEAVE_EARLY:
                            switch ($request['leave_count_type'])
                            {
                                case AbsentRequestModel::LEAVE_COUNT_TYPE_IGNORE:
                                    $turnOnStatus = 'approved_request';
                                    $class        = \App\Models\SettingsModel::DATE_STYLE_NORMAL;
                                    ($request['absent_type'] == AbsentRequestModel::ABSENT_TYPE_WHOLE_DAY) ? $totalDayAbsentApproved++ : $totalDayComeLateApproved++;
                                    break;
                                case AbsentRequestModel::LEAVE_COUNT_TYPE_PAID_LEAVE:
                                    $turnOnStatus = 'anualyleave_request';
                                    $class        = \App\Models\SettingsModel::DATE_STYLE_NORMAL;
                                    ($request['absent_type'] == AbsentRequestModel::ABSENT_TYPE_WHOLE_DAY) ? $totalDayAbsentApproved++ : $totalDayComeLateApproved++;
                                    break;
                                case AbsentRequestModel::LEAVE_COUNT_TYPE_PAY_CUT:
                                    $turnOnStatus = 'rejected_request';
                                    $class        = \App\Models\SettingsModel::DATE_STYLE_ABSENT;
                                    break;
                                case AbsentRequestModel::LEAVE_COUNT_TYPE_ADD_PAID_LEAVE:
                                    $turnOnStatus = 'anualyadd_request';
                                    break;
                            }
                            break;
                    }

                    if ($allCheckin[$request['request_date']]['class'] != \App\Models\SettingsModel::DATE_STYLE_WEEKEND) // Only change class for work days
                        $allCheckin[$request['request_date']]['class'] = $class;
                }

                $allCheckin[$request['request_date']][$turnOnStatus][] = array();
            }
        }

        foreach ($allCheckin as &$check)
        {
            if ($check['workHours'] > 0 && $check['workHours'] < 8 && $this->settings->consider_late_if_not_enough_8_hours && $check['class'] == \App\Models\SettingsModel::DATE_STYLE_NORMAL)
            {
                $check['class'] = \App\Models\SettingsModel::DATE_STYLE_NOT_ENOUGH_TIME;
            }

            if ($check['class'] == \App\Models\SettingsModel::DATE_STYLE_ABSENT)
                $totalDayAbsent++;
            if ($check['class'] == \App\Models\SettingsModel::DATE_STYLE_COME_LATE)
                $totalDayComeLate++;
        }


        $this->assign("fullname", $userInfo['fullname']);
        $this->assign("headers", $headers);
        $this->assign("checkPoints", $allCheckin);
        $this->assign("totalWorkingDayInMonth", $totalWorkingDayInMonth);
        $this->assign("totalDayAbsent", $totalDayAbsent);
        $this->assign("totalDayComeLate", $totalDayComeLate);
        $this->assign("totalDayAbsentApproved", $totalDayAbsentApproved);
        $this->assign("totalDayComeLateApproved", $totalDayComeLateApproved);
        $this->assign("userid", $userId);

        return $this->render();
    }

    public function list($currentMonth = -1, $currentYear = -1)
    {
        if (!$this->session->isAdmin && !$this->session->isLeader)
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (!isset($_GET['showOnlyNotApproved']))
        {
            $showOnlyNotApproved = true;
        }
        else
        {
            $showOnlyNotApproved = $this->request->getGet('showOnlyNotApproved');
        }


        $startDateData = "";
        $endDateData   = "";

        $this->settings->getStartAndEndDateForMonth($currentMonth, $currentYear, $startDateData, $endDateData, $currentMonth, $currentYear, false);

        $this->genMonthDate($currentMonth, $currentYear, $currentYear);

        $listRequests = $this->absentRequest->findByMonth($startDateData, $endDateData, $showOnlyNotApproved, false, $this->session->isAdmin ? '' : $this->session->team);

        $listCountComeLate = array();

        foreach ($listRequests as &$request)
        {

            if (
                    $request['approve_status'] != AbsentRequestModel::APPROVE_STATUS_REJECTED &&
                    ($request['absent_type'] == AbsentRequestModel::ABSENT_TYPE_COME_LATE || $request['absent_type'] == AbsentRequestModel::ABSENT_TYPE_LEAVE_EARLY)
            )
            {
                if (empty($listCountComeLate[$request['user_id']]))
                {
                    $listCountComeLate[$request['user_id']] = 1;
                }
                else
                {
                    $listCountComeLate[$request['user_id']]++;
                }

                $request['note'] = "(Lần {$listCountComeLate[$request['user_id']]})";
            }
            else
            {
                $request['note'] = '';
            }

            if ($request['absent_type'] == AbsentRequestModel::ABSENT_TYPE_ADD_PAID_LEAVE)
            {
                $request['isabsent']   = 'hidden';
                $request['isaddleave'] = '';
                $request['iscomelate'] = '';
            }
            elseif (
                    $request['absent_type'] == AbsentRequestModel::ABSENT_TYPE_COME_LATE || $request['absent_type'] == AbsentRequestModel::ABSENT_TYPE_LEAVE_EARLY || $request['absent_type'] == AbsentRequestModel::ABSENT_TYPE_FORGOT_CHECKIN || $request['absent_type'] == AbsentRequestModel::ABSENT_TYPE_FORGOT_CHECKOUT || $request['absent_type'] == AbsentRequestModel::ABSENT_TYPE_WORK_AT_HOME
            )
            {
                $request['isabsent']   = '';
                $request['isaddleave'] = 'hidden';
                $request['iscomelate'] = 'hidden';
            }
            else
            {
                $request['isabsent']   = '';
                $request['isaddleave'] = 'hidden';
                $request['iscomelate'] = '';
            }

            $request['absent_type']      = AbsentRequestModel::ABSENT_TYPE_NAME[$request['absent_type']];
            $request['approve_status']   = AbsentRequestModel::APPROVE_STATUS_NAME[$request['approve_status']];
            $request['leave_count_type'] = AbsentRequestModel::LEAVE_COUNT_NAME[$request['leave_count_type']];
        }

        $startDateData = explode("-", $startDateData);
        $startDateData = "{$startDateData[2]}-{$startDateData[1]}-{$startDateData[0]}";
        $endDateData   = explode("-", $endDateData);
        $endDateData   = "{$endDateData[2]}-{$endDateData[1]}-{$endDateData[0]}";
        $this->assign('listRequests', $listRequests);
        $this->assign('startDate', $startDateData);
        $this->assign('endDate', $endDateData);
        $this->assign('showOnlyNotApproved', $showOnlyNotApproved ? 'checked' : '');

        return $this->render();
    }

    public function quickcheck($type, $userId, $date)
    {
        if (!$this->session->isAdmin)
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $date = explode('-', $date);

        $date = "{$date[2]}-{$date[1]}-{$date[0]}";

        $data = array(
            'user_id'          => $userId,
            'request_date'     => $date,
            'approve_status'   => AbsentRequestModel::APPROVE_STATUS_APPROVED,
            'leave_count_type' => AbsentRequestModel::LEAVE_COUNT_TYPE_IGNORE,
        );

        switch ($type)
        {
            case 1:
                $data['absent_type']   = AbsentRequestModel::ABSENT_TYPE_FORGOT_CHECKIN;
                $data['request_group'] = -$this->session->userId;
                break;
            case 2:
                $data['absent_type']   = AbsentRequestModel::ABSENT_TYPE_FORGOT_CHECKOUT;
                $data['request_group'] = -$this->session->userId;
                break;
        }

        try
        {
            $this->absentRequest->insert($data);
        }
        catch (\CodeIgniter\Database\Exceptions\DatabaseException $exc)
        {
            
        }

        return redirect()->to("/request/index/$userId");
    }

    public function updatestatus($id, $status)
    {
        if (!$this->session->isAdmin)
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $request = $this->absentRequest->findFirstById($id);

        if (empty($request))
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = array();

        switch ($status)
        {
            case 1://Duyệt Trừ phép
                $data['approve_status']   = AbsentRequestModel::APPROVE_STATUS_APPROVED;
                $data['leave_count_type'] = AbsentRequestModel::LEAVE_COUNT_TYPE_PAID_LEAVE;
                break;
            case 3://Duyệt, không trừ phép, không trừ lương
                $data['approve_status']   = AbsentRequestModel::APPROVE_STATUS_APPROVED;
                $data['leave_count_type'] = AbsentRequestModel::LEAVE_COUNT_TYPE_IGNORE;
                break;
            case 4://Duyệt cộng thêm phép năm
                $data['approve_status']   = AbsentRequestModel::APPROVE_STATUS_APPROVED;
                $data['leave_count_type'] = AbsentRequestModel::LEAVE_COUNT_TYPE_ADD_PAID_LEAVE;
                break;
            case 2://Từ chối, sẽ trừ lương
                $data['approve_status']   = AbsentRequestModel::APPROVE_STATUS_REJECTED;
                if ($request['absent_type'] == AbsentRequestModel::ABSENT_TYPE_ADD_PAID_LEAVE)
                {
                    $data['leave_count_type'] = AbsentRequestModel::LEAVE_COUNT_TYPE_UNKNOW;
                }
                else
                {
                    $data['leave_count_type'] = AbsentRequestModel::LEAVE_COUNT_TYPE_PAY_CUT;
                }
                break;
            default :
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->absentRequest->update($request['id'], $data);

        return redirect()->to("/request/list");
    }

}
