<?
class Route{

    static function start(){

        // контроллер и действие по умолчанию
        $controller_name = 'Main';
        $action_name = 'index';
        
        $routes = explode('/', $_SERVER['REQUEST_URI']);
        if(!empty($routes[1])) $controller_name = $routes[1];
        if(!empty($routes[2])) $action_name = $routes[2];

        // добавляем префиксы
        $model_name = 'Model_' . $controller_name;
        $controller_name = 'Controller_' . $controller_name;
        $action_name = 'action_' . $action_name;

        // подцепляем файл с классом модели (файла модели может и не быть)
        $model_file = strtolower($model_name) . '.php';
        $model_path = "core/models/" . $model_file;
        if(file_exists($model_path))
            include "core/models/" . $model_file;

        // подцепляем файл с классом контроллера
        $controller_file = strtolower($controller_name) . '.php';
        $controller_path = "core/controllers/" . $controller_file;
        if(file_exists($controller_path))
            include "core/controllers/" . $controller_file;
        else{
            /*
            правильно было бы кинуть здесь исключение,
            но для упрощения сразу сделаем редирект на страницу 404
            */
            Route::error404();
        }
        
        // создаем контроллер
        $controller = new $controller_name;
        $action = $action_name;
        
        if(method_exists($controller, $action))
            $controller->$action(); // вызываем действие контроллера
        else Route::error404(); // здесь также разумнее было бы кинуть исключение

    }
    
    function error404(){

        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:' . $host . '404');
        
    }
    
}