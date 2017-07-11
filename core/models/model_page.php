<?

class Model_Page extends Model{

    private $id;

    public function __construct($id){
        
        $this->id = $id;
    }

    function getData(){

        $stmt = db()->prepare("SELECT `title`, `content`, `author` FROM `pages` WHERE `id` = ?");

        $stmt->bind_param("i", $id);
        $id = $this->id;

        $stmt->execute();
        $result = $stmt->get_result();
        $ret = $result->fetch_assoc();

        $stmt->close();
        return $ret;
    }

}