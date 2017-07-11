<?

include(Route::$path['controller']."Controller_404.php");

class Controller_Page extends Controller{

    public function __construct($args){

        parent::__construct($args);

        if(!isset($this->arguments[3]) && empty($this->arguments[2]) || !isset($this->arguments[2])) $this->arguments[2] = 1;
        $this->model = new Model_Page($this->arguments[2]);
    }

    public function start(){

        $data = $this->model->getData();

        if(count($data) === 0){
            $tmp = new Controller_404($this->arguments[2]);
            $tmp->start();

            return;
        }

        View::generate("page.php", $data);
    }
}