<?php

class Widget_Sidebar extends Widget {

    private static $itemFlag = array(
            'eval' => 1,
            'token' => 2
        );

    private static $userItemFlag = array(
            'home' => 1,
            'feed' => 2,
            'im' => 4,
            'friends' => 8,
            'groups' => 16,
            'photo' => 32,
            'audio' => 64,
            'video' => 128,
            'games' => 256
        );

    private $menu = array();

    public function __construct() {

        $menu = array();

        if(!User::isUserLoggedIn()) array_push($menu, self::makeMenuItem("login", "Вход"));
        else {

            //
        }

        $this->menu = $menu;
    }

    private function makeMenuItem(

            $class = "split",
            $text = "",
            $action = null,
            $flags = 0
    ) {
        
        $ret = array(
                'class' => $class,
                'text' => $text
            );

        if(is_null($action) && $class !== "split") $action = "/{$class}";

        if(empty($action) || !is_null($action)) $ret['action'] = $action;

        if(($flags & self::$itemFlag['eval']) !== 0) $ret['eval'] = true;
        if(($flags & self::$itemFlag['token']) !== 0)
            $ret['token'] = User::getCurrentUser()->getKeychain()->generateToken();

        return $ret;
    }

    public function place() {
        
        $data = array(
                'menu' => $this->menu
            );

        $this->view = new View($data, "sidebar");
        $this->view->place();
    }
}