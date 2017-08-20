<?
//Route::loadModel("Admin");

class Controller_Admin extends Controller{

    public function hub(){

        $data = array();

        //

        $this->view = new View($data, "admin/hub");
        $this->view->place();

    }

    public function users(){

        $data = array();

        //

        $this->view = new View($data, "admin/users");
        $this->view->place();
    }

    public function feedback(){

        $data = array();

        //

        $this->view = new View($data, "admin/users");
        $this->view->place();
    }

    public function start(){

        if(!User::isCurrentUserCan(User::$permission['adminpanel'])) Util::error404(__FILE__, __LINE__);
        
        $args = Route::getArgument();

        if(!isset($args[2]) || empty($args[2]) ||
            !method_exists($this, $args[2])) $args[2] = "hub";

        $method = $args[2];
        $this->$method();
    }

}
