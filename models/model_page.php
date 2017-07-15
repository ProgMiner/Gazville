<?

class Model_Page extends Model{

    private $id;

    public function __construct($id){
        
        $this->id = $id;
    }

    function getData(){

        $stmt = db()->prepare("SELECT `title`, `content`, `author` FROM `pages` WHERE `id` = ?")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("i", $id) or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $id = $this->id;

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $ret = $result->fetch_assoc();

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        return $ret;
    }

}