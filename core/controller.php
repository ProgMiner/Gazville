<?

abstract class Controller {
    
    protected $argument;
    protected $model;
    protected $view;
    
    public function __construct(array $args){

        $this->argument = $args;
    }
    
    public abstract function start();
    public static function preroute($url){return $url;}
}