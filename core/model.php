<?
abstract class Model{

    protected $data;
    protected $changed = true;

    public abstract function getData();
}