<?
abstract class Route{

    public static $default_controller_name = "Page";
    public static $notfound_controller_name = "404";

    public static $path = array(
            'model' => "core/models/",
            'controller' => "core/controllers/"
        );

    public static $prefix = array(
            'model' => "Model_",
            'controller' => "Controller_"
        );

    public static function start(){

        $controller_name = Route::$default_controller_name;
        
        $args = explode("/", $_SERVER['REQUEST_URI']);
        {
            $last = count($args) - 1;
            $args[$last] = explode("?", $args[$last]);
            $args[$last] = $args[$last][0];
        }

        if(!empty($args[1])) $controller_name = $args[1];

        $model_name = Route::$prefix['model'] . $controller_name;
        $controller_name = Route::$prefix['controller'] . $controller_name;

        $model_file = strtolower($model_name) . ".php";
        $model_path = Route::$path['model'] . $model_file;

        if(file_exists($model_path)) include($model_path);

        for($i = 0; $i < 2; $i++){

            $controller_file = strtolower($controller_name) . ".php";
            $controller_path = Route::$path['controller'] . $controller_file;
            if(file_exists($controller_path)) break;

            $controller_name = Route::$prefix['controller'] . Route::$notfound_controller_name;
        }
        if(!file_exists($controller_path)) Util::_die("Not Found controller doesn't exists!", __FILE__, __LINE__);

        include($controller_path);

        $controller = new $controller_name($args);
        $controller->start();
    }
}