<?

class Controller_Page extends Controller{

    function __construct($args){

        parent::__construct($args);
        $this->model = new Model_Page();
    }

    function start(){

        View::generate("main.php", View::$default_template_view, $this->model->getData());
    }
}