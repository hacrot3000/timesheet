<?php

namespace App\Controllers;

use App\Models\SettingsModel;
use App\Models\UsersModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use PhpParser\Node\Stmt\TryCatch;
use Psr\Log\LoggerInterface;

class Install extends Controller
{

    private $viewData = [];

    public function __construct()
    {
        $this->assign('site_url', site_url());
        $this->assign('base_url', base_url());
    }

    public function index()
    {

        return $this->render();
    }

    public function upload()
    {
        if (empty($_FILES["fileToUpload"]["tmp_name"]))
        {
            return "No data file";
        }

        $sql = file_get_contents($_FILES["fileToUpload"]["tmp_name"]);
        $rootpass = $_POST['password'];

        $host = getenv('MYSQL_HOST');
        $db = getenv('MYSQL_DB');
        $pass = getenv('MYSQL_PASS');
        $port = getenv('MYSQL_PORT');
        $user = getenv('MYSQL_USER');

        mysqli_report(0);

        try {

            $mysqli = new \mysqli($host, "root", $rootpass, "", $port);

            if ($mysqli->connect_errno) {
                return "Failed to connect to MySQL: " . $mysqli->connect_error;
            }

            $check = $mysqli->query("CREATE USER IF NOT EXISTS '$user'@'%' IDENTIFIED BY '$pass'");
            if (!$check)
            {
                return "Error description: " . $mysqli->error;
            }

            $check = $mysqli->query("ALTER USER '$user'@'%' IDENTIFIED BY '$pass'");
            if (!$check)
            {
                return "Error description: " . $mysqli->error;
            }

            $check = $mysqli->query("GRANT ALL ON $db.* TO '$user'@'%'");
            if (!$check)
            {
                return "Error description: " . $mysqli->error;
            }

            $mysqli = new \mysqli($host, $user, $pass, $db, $port);

            if ($mysqli->connect_errno) {
                return "Failed to connect to MySQL: " . $mysqli->connect_error;
            }

            $check = $mysqli->multi_query($sql);
            if (!$check)
            {
                return "Error description: " . $mysqli->error;
            }

            $mysqli->close();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $this->render();
    }

    protected function render($viewname = '')
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


        $content .= $parser->render("$viewname.php");


        return $content;
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
}
