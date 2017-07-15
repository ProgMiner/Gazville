<?

class Controller_Page extends Controller{

    public function __construct(array $args){

        parent::__construct($args);

        if(!isset($this->arguments[3]) && empty($this->arguments[2]) || !isset($this->arguments[2])) $this->arguments[2] = 1;
        $this->model = new Model_Page($this->arguments[2]);
    }

    public function start(){

        $data = $this->model->getData();

        if(count($data) === 0){

            Util::log("404 Not Found", __FILE__, __LINE__);

            header("HTTP/1.1 404 Not Found");
            header("Status: 404 Not Found");
            header("Location: /404");

            return;
        }

        $this->view = new View($data, "page.php");
        $this->view->place();
    }
}
