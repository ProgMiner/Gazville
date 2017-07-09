<?

class Controller_404 extends Controller{

    function action_main(){

        $this->view->generate('404.php', 'page.php');

    }

}