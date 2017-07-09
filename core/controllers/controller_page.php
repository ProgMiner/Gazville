<?

class Controller_Page extends Controller{

    function __construct(){

        parent::__construct();
        $this->model = new Model_Page();

    }

    function action_main(){

        $this->view->generate('main.php', 'page.php', $this->model->getData());

    }

}