<?

abstract class Controller {
    
    public $arguments;
    public $model;
    
    public function __construct($args){

        $this->arguments = $args;
    }
    
    public abstract function start();
}