<?
class Controller_Login extends Controller{

    private function logout(){

        if(isset($_POST['token']) && User::isUserLoggedIn() && User::getCurrentUser()->getKeychain()->checkToken($_POST['token']))
            User::logout(User::getCurrentUserId());

        Util::redirect("/", __FILE__, __LINE__);
    }

    public function start(){

        if(Route::getArgument(2) === "logout") return $this->logout();

        $data = array();
        while(isset($_POST['login']) && isset($_POST['password']) && 
            !empty($_POST['login']) && !empty($_POST['password'])){

            $error = User::login($_POST['login'], md5($_POST['password']), isset($_POST['remember']));

            if($error === 0) break;

            $data['error'] = $error;
            break;
        }

        if(User::isUserLoggedIn()) Util::redirect("/", __FILE__, __LINE__);

        $data['default'] = array(
                'login' => "",
                'password' => "",
                'remember' => "on"
            );

        foreach($data['default'] as $field => $value)
            if(isset($_POST[$field])) $data['default'][$field] = $_POST[$field];

        if($data['default']['remember'] !== "") $data['default']['remember'] = " checked";
        
        $this->view = new View($data, "login");
        $this->view->place();
    }

    public static function preroute($url){

        if($url === "/logout") $url = "/login/logout";

        return $url;
    }

}
