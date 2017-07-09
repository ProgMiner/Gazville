<?

class Controller_Page extends Controller{

    function action_main(){

        $this->view->generate('main.php', 'page.php');

    }

}