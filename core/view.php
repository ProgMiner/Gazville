<?
class View{
    
    public $template_view = 'main.php';

    /*
	$content_file - виды отображающие контент страниц;
	$template_file - общий для всех страниц шаблон;
	$data - массив, содержащий элементы контента страницы. Обычно заполняется в модели.
	*/
    
    function generate($content_view, $template_view = '', $data = null){

        if(empty($template_view)) $template_view = $this->template_view;
        $content_view = 'core/views/' . $content_view;

        if(is_array($data)) extract($data); // преобразуем элементы массива в переменные
        
        /*
		динамически подключаем общий шаблон (вид),
		внутри которого будет встраиваться вид
		для отображения контента конкретной страницы.
		*/
        include 'core/views/templates/' . $template_view;
        
    }

}