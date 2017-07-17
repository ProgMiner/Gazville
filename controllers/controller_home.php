<?

class Controller_Home extends Controller{

    public function start(){

        if(User::isUserLoggedIn()) Util::redirect("/feed");
        
        Util::redirect("/info", __FILE__, __LINE__);
    }
}
