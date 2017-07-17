<?

Route::loadModel("Page");

class Controller_Page extends Controller{

    public function __construct(array $args){

        parent::__construct($args);

        if(!isset($this->arguments[3]) && empty($this->arguments[2]) || !isset($this->arguments[2])) $this->arguments[2] = 1;
        $this->model = new Model_Page($this->arguments[2]);
    }

    public function start(){

        $data = $this->model->getData();

        if(count($data) === 0) Util::error404();

        $this->view = new View($data, "page.php");
        $this->view->place();
    }

    public static function preroute($url){

        $pages = Model_Page::getPagesURLs();

        foreach($pages as $page_url => $id)
            if($url === "/{$page_url}"){

                $url = "/page/{$id}";
                break;
            }
        
        return $url;
    }
}
