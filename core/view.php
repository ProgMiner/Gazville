<?
class View{
    
    public $template_view = "main";
    
    function generate($content_view, $template_view = $this->template_view, $data = null){
        if(is_array($data)) extract($data); // преобразуем элементы массива в переменные
        
        include '../views/' . $template_view;
    }

}