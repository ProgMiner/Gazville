<?
class Widget_Sidebar extends Widget{
	
	public static $link = array(
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

    public function place(){
        
        function makeLink($class = "split", $text = "", $action = null, $eval = false, $token = false) {
            
            if(is_null($action)) {
                
                if($class !== "split") $action = "/{$class}";
                else $action = "";
            }
            
            return array(
                    'class' => $class,
                    'text' => $text,
                    'action' => $action,
                    'eval' => $eval,
                    'token' => $token
                );
        }

        $data = array(
                'menu' => array()
            );

        if(!User::isUserLoggedIn()) array_push(
                $data['menu'],
                makeLink("login", "Вход")
            );
        else {
            
            array_push($data['menu'], makeLink(
                    "search",
                    '$this->placeView("searchform.php");',
                    "",
                    true
                ));
                
            array_push($data['menu'], makeLink());
            
            // TODO: Make user-submenu
            
            array_push($data['menu'], makeLink(
                    "logout",
                    "Выход",
                    "/logout",
                    false,
                    true
                );
                
            array_push($data['menu'], makeLink());
            
            // Hideable links
            
            {
                $user_links = User::getCurrentUserData("sidebar");
                
                $menu = array(
                        makeLink(
                                "home",
                                "Моя страница",
                                "/" . User::getCurrentUserData("login")
                            ), makeLink(
                                "feed",
                                "Новости"
                            ), makeLink(
                                "im",
                                "Сообщения"
                            ), makeLink(
                                "friends",
                                "Друзья"
                            ), makeLink(
                                "groups",
                                "Группы"
                            ), makeLink(
                                "photo",
                                "Фотографии"
                            ), makeLink(
                                "audio",
                                "Аудиозаписи"
                            ), makeLink(
                                "video",
                                "Видеозаписи"
                            ), makeLink(
                                "games",
                                "Игры"
                            )
                    );
                    
                 foreach($menu as $i => $link)
                     if(($user_links & self::$links[$link['class']]) === 0)
                         unset($menu[$i]);
                         
                 $data['menu'] = array_merge($data['menu'], $menu);
            }
        }
            

        //$this->view = new View($data, "user");
        //$this->view->place();
    }
}