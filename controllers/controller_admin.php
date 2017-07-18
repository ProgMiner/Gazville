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

    public function start(){

        if(!User::isCurrentUserCan(User::$permission['adminpanel'])) Util::error404(__FILE__, __LINE__); 

        if(!isset($this->argument[2]) || empty($this->argument[2]) ||
            !method_exists($this, $this->argument[2])) $this->argument[2] = "hub";

        $method = $this->argument[2];
        $this->$method();
    }

}