<?

abstract class Controller {
    
    private $arguments;
    private $model;
    private $view;
    
    public function __construct(array $args){

        $this->arguments = $args;
    }
    
    public abstract function start();
}