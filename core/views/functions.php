<?
function gazville_init(){
    $request = $_SERVER['REQUEST_URI'];

    if(!current_user_can("administrator")){
        if(!isset($_COOKIE['alphatest']) && strpos($request, "/wp-login.php") !== 0){
            wp_redirect("https://vk.com/club70404044");
            die();
        }

        show_admin_bar(false);        
    }
    if(is_admin_bar_showing()) add_action("wp_head", "gazville_sidebar_top");

    if(strpos($request, "/profile") === 0 && preg_match("#^/profile(/[0-9]*)?(/settings)?$#", $request)){
        query_posts("page_id=45");
    }
}
add_action("init", "gazville_init");

function gazville_sidebar_top(){?>
    <style>
        #sidebar{
            top: 32px;
        }
        @media screen and (max-width: 782px){
            #sidebar{
                top: 46px;
            }
        }
    </style>
<?}

function gazville_setup(){
    add_theme_support("title-tag");
    //register_nav_menu("primary", __("Primary Menu"));
}
add_action("after_setup_theme", "gazville_setup");

function gazville_scripts(){
    wp_register_style("gazville_style", get_template_directory_uri() . "/style.css?" . time());
    wp_register_style("gazville_loading_style", get_template_directory_uri() . "/assets/styles/loading.css");
    wp_register_style("gazville_sidebar_style", get_template_directory_uri() . "/assets/styles/sidebar.css");
    wp_register_style("gazville_header_style", get_template_directory_uri() . "/assets/styles/header.css");

    wp_register_script("gazville_script", get_template_directory_uri() . "/assets/js/script.js", array('jquery'));

    wp_enqueue_style("gazville_style");
    wp_enqueue_style("gazville_loading_style");
    wp_enqueue_style("gazville_sidebar_style");
    wp_enqueue_style("gazville_header_style");

    wp_enqueue_script("gazville_script");
}
add_action("wp_enqueue_scripts", "gazville_scripts");

function gazville_exclude_pages($query) {
    if($query->is_search){
        $query->set("post_type", "post");
    }
    return $query;
}
add_filter("pre_get_posts", "gazville_exclude_pages");

function gazville_avatar_url($email){
    return "https://www.gravatar.com/avatar/" . md5($email) . "?s=140&d=" . urlencode(get_template_directory_uri() . "/assets/images/avatar.png");
}
