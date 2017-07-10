<?

class Controller_404 extends Controller{

    function start(){

        Util::log("404 Not Found", __FILE__, __LINE__);

        header("HTTP/1.1 404 Not Found");
        header("Status: 404 Not Found");
        View::generate("404.php");
    }
}