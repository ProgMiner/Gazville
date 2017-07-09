<?
class View{
    
    public $template_view = 'main.php';
    
    function generate($content_view, $template_view = '', $data = null){

        if(empty($template_view)) $template_view = $this->template_view;
        $content_view = 'core/views/' . $content_view;

        if(is_array($data)) extract($data); // преобразуем элементы массива в переменные
        
        include 'core/views/templates/' . $template_view;
        
    }

}