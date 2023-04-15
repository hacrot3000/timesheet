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

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 * 
 * @property-read UsersModel $userModel
 * 
 */
abstract class BaseController extends Controller
{

    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;

    /**
     * @var SettingsModel
     */
    protected $settings;

    /**
     * @var UsersModel
     */
    protected $users;
    protected $parser;
    private $viewData = [];

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        $this->session = \Config\Services::session();

        $this->parser = \Config\Services::parser();

        $this->users    = new UsersModel();
        $this->settings = new SettingsModel();

        $this->assign('site_url', site_url());
        $this->assign('base_url', base_url());
        
        if (empty($this->session->userId))
        {
            $this->users->authencationByRememberKey();
        }

        if (empty($this->session->userId))
        {
            
            $this->session->isAdmin = false;
            $this->session->isLead = false;
            $this->session->userId  = 0;

            $this->assign('is_logged_user', array());
            $this->assign('not_logged_user', array(array()));
        }
        else
        {
            $this->assign('is_logged_user', array(array()));
            $this->assign('not_logged_user', array());
        }

        if ($this->session->isAdmin)
        {
            $this->assign('is_admin_funs', array(array()));
        }
        else
        {
            $this->assign('is_admin_funs', array());
        }

        if ($this->session->isLeader)
        {
            $this->assign('is_lead_funs', array(array()));
        }
        else
        {
            $this->assign('is_lead_funs', array());
        }
    }

    protected function assign($key, $value = '')
    {
        if (is_array($key))
        {
            foreach ($key as $k => &$v)
            {
                $this->viewData[$k] = $v;
            }
        }
        else
        {
            $this->viewData[$key] = $value;
        }
    }

    protected function assignAppend($key, $value)
    {
        if (empty($this->viewData[$key]))
        {
            $this->assign($key, $value);
        }
        else
        {
            $this->viewData[$key] .= $value;
        }
    }

    protected function render($viewname = '', $withHeader = true, $withFooter = true)
    {
        $parser     = \Config\Services::parser();
        $content    = "";
        $router     = service('router');
        $controller = strtolower(class_basename($this));

        if (empty($viewname))
        {
            $viewname = $controller . "." . $router->methodName();
        }

        $parser = $parser->setData($this->viewData);

        if ($withHeader)
        {
            $content .= $parser->render("modules/header");
        }

        $content .= $parser->render("$viewname.php");

        if ($withFooter)
        {
            $content .= $parser->render("modules/footer");
        }

        return $content;
    }

    protected function showMessages($subject, $messages, $next = "")
    {
        if (empty($next))
        {
            $this->assign('url', "javascript:history.back()");
            $this->assign('text', "Trở lại trang trước");
        }
        else
        {
            $this->assign('url', $next);
            $this->assign('text', "Tiếp tục");
        }

        if (is_array($messages))
        {
            $amessages = array();

            foreach ($messages as $m)
            {
                $amessages[] = ['content' => $m];
            }
        }
        else
        {
            $amessages = [['content' => $messages]];
        }

        $this->assign('messages', $amessages);
        $this->assign('subject', $subject);

        return $this->render('messages');
    }

    protected function genMonthDate($rawCurrentMonth, $currentYear)
    {

        $months = array();
        $years  = array();
        for ($m = 1; $m <= 12; $m++)
        {
            $selected = "";
            if ($m == $rawCurrentMonth)
            {
                $selected = "selected";
            }

            $months[] = array(
                'selected' => $selected,
                'value'    => $m
            );
        }

        $readlCurrentYear = date("Y");
        for ($y = $currentYear - 1; $y <= $readlCurrentYear; $y++)
        {
            $selected = "";
            if ($y == $currentYear)
            {
                $selected = "selected";
            }

            $years[] = array(
                'selected' => $selected,
                'value'    => $y
            );
        }
        $this->assign("months", $months);
        $this->assign("years", $years);
    }
    
    protected function printDebug($param)
    {
        return "<pre>" . var_export($param, true);
    }

}
