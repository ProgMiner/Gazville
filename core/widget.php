<?php

abstract class Widget {
    
    protected $model;
    protected $view;
    
    public abstract function place();
}