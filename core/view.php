<?php

class View{

    public static $default_view = "index";
    public static $charset = "utf8";
    
    private $data;
    private $view;
    private $path;
    
    public function __construct(array $data = array(), $view = null) {

        $this->data = $data;

        while(!preg_match("/^([\s\S]*\/)?([\s\S]+?)(\.php)?$/", $view, $matches)) $view = self::$default_view;

        $this->path = Route::$path['view'] . $matches[1];
        $this->view = $matches[2];
    }

    private function setData($data, $value = "") {

        if(is_array($data)) {

            foreach($data as $key => $value)
                $this->data[$key] = $value;

            return $this->data;
        }

        $this->data[$data] = $value;

        return $this->data;
    }

    private function placeView($view) {

        while(!preg_match("/^([\s\S]+?)(\.php)?$/", $view, $matches)) $view = self::$default_view;
        $view = $matches[1];

        extract($this->data);
        include("{$this->path}{$view}.php");
    }

    public function place() {

        $this->placeView($this->view);
    }
}
