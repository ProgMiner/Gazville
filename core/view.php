<?

class View{

    public static $default_view = "index.php";
    public static $charset = "utf8";
    
    private $data;
    private $view;
    
    public function __construct(array $data = array(), $view = null){

        if(is_null($view)) $view = View::$default_view;

        $this->data = $data;
        $this->view = $view;
    }

    public function placeView($view){

        extract($this->data);
        include(Route::$path['view'] . $view);
    }

    public function place(){

        $this->placeView($this->view);
    }
}
