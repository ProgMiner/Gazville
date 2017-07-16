<?
abstract class Route{

    public static $default_controller_name = "Page";

    public static $path = array(
            'view' => "views/",
            'model' => "models/",
            'controller' => "controllers/"
        );

    public static $prefix = array(
            'model' => "Model_",
            'controller' => "Controller_"
        );

    private static $controllers = array();

    public static function loadModel($model){

        include_once(self::$path['model'] . self::$prefix['model'] . $model . ".php");
    }

    public static function findController($controller){

        $controller = strtolower($controller);
        foreach(self::$controllers as $loaded_controller)
            if(strtolower($loaded_controller) === $controller)
                return $loaded_controller;
        
        return false;
    }

    private static function loadControllers(){

        $path = self::$path['controller'];
        $dir = scandir($path, SCANDIR_SORT_NONE);

        $prefix = self::$prefix['controller'];
        foreach($dir as $file)
            if(preg_match("/^{$prefix}[a-z]*\.php$/i", $file))
                include_once("{$path}{$file}");

        $classes = get_declared_classes();
        foreach($classes as $class)
            if(preg_match("/^{$prefix}[A-z]*$/", $class))
                self::$controllers[count(self::$controllers)] = $class;
    }

    private static function prerouting($url){

        foreach (self::$controllers as $controller)
            $url = $controller::preroute($url);

        return $url;
    }

    public static function start(){

        self::loadControllers();

        $controller_name = self::$default_controller_name;
        
        $args = explode("/", self::prerouting($_SERVER['REQUEST_URI']));
        {
            $last = count($args) - 1;
            $args[$last] = explode("?", $args[$last]);
            $args[$last] = $args[$last][0];
        }

        if(!empty($args[1])) $controller_name = $args[1];
        $controller_name = self::$prefix['controller'] . $controller_name;

        if(($controller_name = self::findController($controller_name)) === false){

            header("Location: /404");
            die();
        }

        Util::log("Controller: {$controller_name}", __FILE__, __LINE__);

        $controller = new $controller_name($args);
        $controller->start();
    }
}
