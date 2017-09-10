<?php

Route::loadModel("Feedback");

class Controller_Feedback extends Controller{

    private $action = "";

    public function __construct() {

        parent::__construct();

        $arg2 = Route::getArgument(2);
        $arg = "";
        if(!is_null($arg2)) $arg = $arg2;

        if($arg === strval(intval($arg))) $arg = "single";
        elseif($arg !== "all") $arg = "";

        if($arg === "single") $this->model = new Model_Feedback(false, $arg2);
        elseif($arg === "all") $this->model = new Model_Feedback(true);
        else $this->model = new Model_Feedback();

        $this->action = $arg;
    }

    public function start() {

        $data = $this->model->getData();

        if(count($data) === 0) $this->action = "";
        if($this->action === "all")
            $data = array(
                    'messages' => $data
                );

        if($this->action === "") {

            $data = array(
                    'last' => $data
                );

            $data['default'] = array(
                    'subject' => "",
                    'content' => "",
                    'email' => ""
                );

            if(isset($_POST['subject']) && !empty($_POST['subject']) && 
                isset($_POST['content']) && !empty($_POST['content']) &&
                ((isset($_POST['email']) && !empty($_POST['email'])) || User::isUserLoggedIn())) {

                if(User::isUserLoggedIn()) $data['status'] = Model_Feedback::sendMessage($_POST['subject'], $_POST['content']);
                else $data['status'] = Model_Feedback::sendMessage($_POST['subject'], $_POST['content'], $_POST['email']);

                if($data['status']) $data['status'] = "success";
                else $data['status'] = "error";

                $data['last'] = array();
            }else{

                foreach($data['default'] as $field => $value)
                    if(isset($_POST[$field])) $data['default'][$field] = $_POST[$field];
            }

            $this->view = new View($data, "feedback");
        }else $this->view = new View($data, "feedback-{$this->action}");

        $this->view->place();
    }
}