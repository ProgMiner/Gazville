<?

class Model_Page extends Model{

    private $id;

    public function __construct($id){
        
        $this->id = $id;
    }

    public function getData(){

        if(!$this->changed) return $this->data;

        $ret = self::$default_data;

        $stmt = db()->prepare("SELECT `page_title`, `page_content`, `user_id` FROM `pages` WHERE `page_id` = ? LIMIT 1")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("i", $id) or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $id = $this->id;

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $ret = array_replace($ret, $result->fetch_assoc());

        if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $this->data = $ret;
        $this->changed = false;

        return $ret;
    }

    public static function getIdByURL($url){

        $stmt = db()->prepare("SELECT `page_id` FROM `pages` WHERE `page_url` = ? LIMIT 1")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("s", $url) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->bind_result($id) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        if(is_null($stmt->fetch())) return false; // URL isn't exists
        if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        return $id;
    }
}