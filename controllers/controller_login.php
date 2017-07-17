<?
class Controller_Login extends Controller{


    public function start(){

        $data = array();
        while(isset($_POST['login']) && isset($_POST['password']) && 
            empty($_POST['login']) && empty($_POST['password'])){

            $error = User::login($_POST['login'], md5($_POST['password']), isset($_POST['remember']));

            if($error === 0) break;

            $data['error'] = $error;
            break;
        }

        if(User::isUserLoggedIn()) Util::redirect("/");
        
        $this->view = new View($data, "login.php");
        $this->view->place();
    }

}