<?php

Route::loadModel("Page");

class Controller_Page extends Controller{

    public function __construct() {

        parent::__construct();

        $this->model = new Model_Page(Route::getArgument(2));
    }

    public function start() {

        $data = $this->model->getData();

        if(count($data) === 0) Util::error404();

        $this->view = new View($data, "page");
        $this->view->place();
    }

    public static function preroute($url) {

        if(!preg_match("/^\/(\w{0,255})$/", $url, $matches)) return $url;
        if(($id = Model_Page::getIdByURL($matches[1])) === false) return $url;
    
        return "/page/{$id}"; 
    }
}
