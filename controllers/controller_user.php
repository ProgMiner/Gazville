<?
class Controller_User extends Controller{

    public function start() {

        if(!User::isUserLoggedIn()) Util::error404();

        $data = array();

        //$this->view = new View($data, "user");
        //$this->view->place();
    }

    public static function preroute($url) {

        if(!preg_match("/^\/(\w{0,255})$/", $url, $matches)) return $url;
        if(($id = Model_User::getIdByLogin($matches[1])) === false) return $url;
    
        return "/user/{$id}"; 
    }
}