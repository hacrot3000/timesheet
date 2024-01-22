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
class UsersModel extends BaseModel
{

    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    // protected $returnType         = 'array';
    // protected $useSoftDeletes     = true;
    protected $allowedFields    = ['username', 'password', 'fullname', 'team', 'is_admin', 'paid_leave_per_year', 'paid_leave_left_this_year', 'paid_leave_left_last_year', 'remember_key', 'email'];

    // protected $useTimestamps      = true;
    // protected $createdField       = '';
    // protected $updatedField       = '';
    // protected $deletedField       = 'deleted_at';
    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;

    public function authencation($username, $password, $remember, $openidKey = '')
    {
        $where = [
            'username' => $username,
            'password' => empty($password)?'':md5(md5($password))
        ];

        if (!empty($openidKey))
        {
            unset($where['password']);
        }

        $user = $this->asArray()
                ->where($where)
                ->first();


        if (!empty($user))
        {
            if (empty($user['password']) && !empty($user['is_admin']))
            {
                return null;
            }
            if ($remember || !empty($openidKey))
            {
                $rememberKey = md5(bin2hex(random_bytes(256)));
                $this->setRememberCookie($rememberKey);
                $this->update($user['id'], ['remember_key' => $rememberKey]);
            }
            $session = \Config\Services::session();

            $session->userId  = $user['id'];
            $session->isAdmin = $user['is_admin'];
            $session->isLeader = $user['is_team_lead'];
            $session->team = $user['team'];

            if (!empty($openidKey) && $user['password'] != $openidKey)
            {
                $this->update($user['id'], ['password' => $openidKey]);
                //$this->update($user['id'], ['remember_key' => $openidKey]);
            }
        }

        return $user;
    }

    public function logout()
    {
        $session          = \Config\Services::session();
        $this->update($session->userId, ['remember_key' => null]);
        $this->setRememberCookie();
        $session->userId  = 0;
        $session->isAdmin = 0;
    }

    protected function setRememberCookie($rememberKey = '')
    {
        if (empty($rememberKey))
        {
            $rememberKey = 'ga-' . uniqid();
        }
        $secure = false;
        if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'))
        {
            $secure = true;
        }

        setcookie("nmnsdh_qgregd", "$rememberKey", time() + 7776000, '/', '', $secure);
    }

    public function authencationByRememberKey()
    {
        helper('cookie');
        $rememberKey = get_cookie('nmnsdh_qgregd');

        if (empty($rememberKey))
        {
            return null;
        }

        $where = [
            'remember_key' => $rememberKey,
        ];

        $user = $this->asArray()
                ->where($where)
                ->first();

        if (!empty($user))
        {
            $session = \Config\Services::session();

            $session->userId  = $user['id'];
            $session->isAdmin = $user['is_admin'];
        }

        return $user;
    }

    public function getAll($team)
    {
        $where = [
                //'role > ' => 0,
        ];

        if (!empty($team))
        {
            $where['team'] = $team;
        }

        $all = $this->asArray()
                ->select("id, username, fullname, team, paid_leave_per_year, paid_leave_left_this_year, paid_leave_left_last_year, is_team_lead")
                ->where($where)
                ->orderBy('username')
                ->findAll();

        return $all;
    }

    public function encryptPassword($id, $password, &$errMess)
    {
        $errMess = "";

        // Validate password strength
        $uppercase    = preg_match('@[A-Z]@', $password);
        $lowercase    = preg_match('@[a-z]@', $password);
        $number       = preg_match('@[0-9]@', $password);
        $specialChars = true; //preg_match('@[^\w]@', $password);

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8)
        {
            $errMess = "Mật khẩu phải dài ít nhất 8 ký tự, có một chữ thường, một chữ hoa, một chữ số.";
            return false;
        }

        $encrypted = md5(md5($password));

        $where = [
            'password' => $encrypted,
            'id != '   => intval($id),
        ];

        $all = $this->asArray()
                ->select("id")
                ->where($where)
                ->orderBy('fullname')
                ->first();

        if (!empty($all))
        {
            $errMess = "Mật khẩu nằm trong danh sách từ khoá không an toàn, xin vui lòng chọn mật khẩu khác.";
            return false;
        }

        return $encrypted;
    }

    public function leaderEmail($team)
    {
        $this->select('email');
        $this->where('is_team_lead', 1);
        $this->where('team', $team);
        $all = $this->findAll();

        $emails = [];

        foreach ($all as $a)
        {
            $emails[] = $a['email'];
        }

        return implode(',', $emails);
    }

}
