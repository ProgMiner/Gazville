<?
class View{
    
    public $template_view = 'main.php';

    /*
        $content_file - шаблон содержимого страницы;
        $template_file - общий шаблон страницы;
        $data - массив, содержащий данные модели. Обычно заполняется в модели.
    */
    
    function generate($content_view, $template_view = '', $data = null){

        if(empty($template_view)) $template_view = $this->template_view; 
        $content_view = 'core/views/' . $content_view;

        if(is_array($data)) extract($data); // Преобразуем элементы массива в переменные
        
        /*
            Динамически подключаем общий шаблон (вид),
            внутри которого будет встраиваться вид
            для отображения контента конкретной страницы.
        */
        include 'core/views/templates/' . $template_view;
        
    }

}