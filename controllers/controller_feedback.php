<?
Route::loadModel("Feedback");

class Controller_Feedback extends Controller{

    public function start(){

        $data = array();

        $this->view = new View($data, "feedback");
        $this->view->place();
    }
}