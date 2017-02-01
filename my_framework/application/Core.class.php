<?php
namespace MyFramework;
use \PDO;

class Core
{
    static protected $_routing = [];
    static private $_render;
    static protected $_pdo;

    public function __construct()
    {
        try
        {
            $user = "my_framework";
            $password = "";
            $database = "my_framework";
            $socket = "/tmp/mysql.sock";
            self::$_pdo = new PDO('mysql:dbname=' . $database .
            ';host=127.0.0.1;charset=utf8;unix_socket=' .
            $socket, $user, $password);
        }
        catch (Exception $e)
        {
            die('La connexion à la base de donnée a échoué: ' .
            $e->getMessage());
        }
    }

    private function routing()
    {
        $URL = explode("/" ,trim($_SERVER['REQUEST_URI'], "/"));
        //set le tableau $_routing
        self::$_routing['controller'] = (isset($URL[1])) &&
        (strlen($URL[1]) != 0) ? $URL[1] : 'default';
        self::$_routing['action'] = (isset($URL[2])) &&
        (strlen($URL[2]) != 0) ? $URL[2] : 'default';

        //Routage Statique
        $sql = "SELECT * FROM routing
        WHERE url = :current_url";
        $req = self::$_pdo->prepare($sql);
        $req->execute(['current_url' => self::$_routing['controller']]);
        $routes = $req->fetch(PDO::FETCH_ASSOC);
        if(!empty($routes)) {
            $array_routes = explode("/",$routes['real_path']);
            self::$_routing['controller'] = $array_routes[0];
            self::$_routing['action'] = $array_routes[1];
        }

        //Routage Dynamique
        $file_class = "controllers/" .
        ucfirst(self::$_routing['controller']) . 'Controller.class.php';

        if (file_exists($file_class))
        {    
            $c = __NAMESPACE__ . '\\' . ucfirst(self::$_routing['controller']) .
            'Controller';
            if(!class_exists($c)) {
            $this->error();
            exit;
            }
            $o = new $c();
            if (!method_exists($o, $a = self::$_routing['action'] .
                'Action')) {
            $this->error();
            exit;
            }
        }
        else  {
            $this->error();
            exit;
        }
    }

    protected function render($params = [], $action = null)
    {
        $action = ($action != null) ? $action : self::$_routing['action'];

        $f = implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'views',
            self::$_routing['controller'], $action]) .
        '.html';
        if (file_exists($f)) {
            $c = file_get_contents($f);
            foreach ($params as $k => $v) {
                $c = preg_replace("/\{\{\s*$k\s*\}\}/", $v, $c);
            }
            self::$_render = $this->layout() .
            preg_replace("/\{\{\s*(.*?)\s*\}\}/", "", $c);
        }
        else {
            $this->error();
            exit;
        }
    }

    public function run()
    {
        $this->routing();
        $c = __NAMESPACE__ . '\\' . ucfirst(self::$_routing['controller']) .
        'Controller';
        $o = new $c();

        if (method_exists($o, $a = self::$_routing['action'] . 'Action'))
        {
            $o->$a();
        }
        else {
            $this->error();
            exit;
        }
        echo self::$_render;
    }

    public function layout($bool = null)
    {

        $path = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
        $tpl = ['path' => $path];
        $path_layout = implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'views',
        'layout.html']);
        $patern = ($this->isLogged(true)) ? "connect" : "disconnect";
        if($this->isLogged(true)) {
            $tpl['name'] = ucfirst($_SESSION['name' . TOKEN]);
        }
        $path_patern = implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'static',
        'patern',$patern . '.patern.html']);
        $layout =  file_get_contents($path_layout) .
        file_get_contents($path_patern);
        if($bool) {
            $path_error = implode(DIRECTORY_SEPARATOR, [dirname(__DIR__),
                'views', 'error404.html']);
            $layout =  file_get_contents($path_error);
        }
        foreach ($tpl as $k => $v) {
            $layout = preg_replace("/\[\{\s*$k\s*\}\]/", $v, $layout);
        }
        return $layout;
    }

    public function isLogged($state = null) {
        if(!empty($state)) {
            if (!isset($_SESSION['login' . TOKEN])) {
                return false;
            }
            return true;
        }
        if (!isset($_SESSION['login' . TOKEN])) {
            header("Location: /" . BASE_URI . "/registration/default");
            exit;
        }
        return true;
    }

    public function isUser() {
        if (!isset($_SESSION['state' . TOKEN])) {
            header("Location: /" . BASE_URI . "/registration/default");
            exit;
        }
        if ($_SESSION['state' . TOKEN] != "0"
            || $_SESSION['state' . TOKEN] != "1") {
            header("Location: /" . BASE_URI . "/default/default");
            exit;
        }
        return true;
    }

    public function isAdmin() {
        if (!isset($_SESSION['state' . TOKEN])) {
            header("Location: /" . BASE_URI . "/default/default");
            exit;
        }
        if ($_SESSION['state' . TOKEN] != "1") {
            header("Location: /" . BASE_URI . "/default/default");
            exit;
        }
        return true;
    }

    public function error() {
        echo $this->layout(true);
    }
}
?>