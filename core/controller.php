<?

abstract class Controller {
    
    protected $arguments;
    protected $model;
    protected $view;
    
    public function __construct(array $args){

        $this->arguments = $args;
    }
    
    public abstract function start();
    public static function preroute($url){return $url;}
}