<?

abstract class View{

    public static $default_template_view = "index.php";

    public static $path = array(
            'view' => "core/views/"
        );
    
    /*
        $content_file - шаблон содержимого страницы;
        $template_file - общий шаблон страницы;
        $data - массив, содержащий данные модели. Обычно заполняется в модели.
    */
    
    public static function generate($template_view = "", $data = array()){

        if(empty($template_view)) $template_view = View::$default_template_view;

        if(is_array($data)) extract($data); // Преобразуем элементы массива в переменные
        
        /*
            Динамически подключаем общий шаблон (вид)
        */
        include View::$path['view'] . $template_view;
    }
}