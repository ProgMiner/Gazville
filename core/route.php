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

    private static function prerouting($url){

        $stmt = db()->prepare("SELECT `pattern`, `replacement` FROM `prerouting` WHERE 1")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        while($row = $result->fetch_assoc())
            $url = preg_replace("#{$row['pattern']}#", $row['replacement'], $url);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        return $url;
    }

    public static function start(){

        $controller_name = Route::$default_controller_name;
        
        $args = explode("/", Route::prerouting($_SERVER['REQUEST_URI']));
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

        $controller_file = strtolower($controller_name) . ".php";
        $controller_path = Route::$path['controller'] . $controller_file;

        if(!file_exists($controller_path)){

            header("Location: /404");
            die();
        }

        Util::log("Controller: {$controller_path}", __FILE__, __LINE__);

        include($controller_path);

        $controller = new $controller_name($args);
        $controller->start();
    }
}
