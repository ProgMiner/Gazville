<?
abstract class Model{

    protected static $default_data = array();

    protected $data;
    protected $changed = true;

    public abstract function getData();
}