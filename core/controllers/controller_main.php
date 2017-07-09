<?

class Controller_Main extends Controller{

    function action_index(){

        $this->view->generate('main.php', 'main.php');

    }

}