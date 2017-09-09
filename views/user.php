<?
function gazville_profile_title($title) {
    if(!is_user_logged_in()) return $title;
    $request = $_SERVER['REQUEST_URI'];
    $userID = get_current_user_ID();
    if(preg_match("#^/profile/([0-9]+)(/settings)?$#", $request, $matches)) $userID = $matches[1];

    $userData = get_userdata($userID);
    if(!$userData) return $title;
    $userName = $userData->display_name;

    if(strpos($request, "/settings") !== false) {
        if($userID == get_current_user_ID()) return "Настройки профиля";
        else return "Настройки профиля {$userName}";
    }

    return $userName;
}
add_filter("pre_get_document_title", "gazville_profile_title");

get_header();

$request = $_SERVER['REQUEST_URI'];
$userID = get_current_user_ID();
if(preg_match("#^/profile/([0-9]+)(/settings)?$#", $request, $matches)) $userID = $matches[1];

$userData = get_userdata($userID);
?>

<div class="post">
    <div class="postsidebar">
        <a style="cursor: default;"><img src="<?echo gazville_avatar_url($userData->user_email);?>" class="postavatar"></a>
        <a class="postauthor" style="text-decoration: none;"><?echo $userData->display_name;?></a>
    </div>
    <div class="postcenter">
        <div class="posttitle"><?echo gazville_profile_title("")?></div>
        <div class="postcontent">
            <?if(!is_user_logged_in()):?>
                Вы не авторизированны на сайте!
            <?else:?>
                
            <?endif;?>
        </div>
    </div>
</div>

<?get_footer();?>
