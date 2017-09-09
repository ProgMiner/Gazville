<?

abstract class Controller {
    
    protected $model;
    protected $view;
    protected $widget = array();
    
    public function __construct() {}
    public abstract function start();

    public static function preroute($url) {return $url;}
}