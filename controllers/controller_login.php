<?
class Controller_Login extends Controller{


    public function start(){

    	if(User::isUserLoggedIn()) {
    		Util::redirect("/");
    	}

        if(isset($_POST['login']) && isset(md5($_POST['password'])) {
        	User::login();
        }
    	
    	$this->view = new View("login.php");
        $this->view->place();
    }

}