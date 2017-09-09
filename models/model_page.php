<?

class Model_Page extends Model{

    private $id;

    public function __construct($id) {

        parent::__construct();
        
        $this->id = $id;
    }

    public function getData() {

        if(!is_null($this->data)) return $this->data;

        $ret = self::$default_data;

        $stmt = db()->prepare("SELECT `page_title`, `page_content` FROM `pages` WHERE `page_id` = ? LIMIT 1")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("i", $id) or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $id = $this->id;

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $ret = array_replace($ret, $result->fetch_assoc());

        if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $ret['title'] = $ret['page_title'];
        $ret['content'] = $ret['page_content'];

        $this->data = $ret;

        return $ret;
    }

    public static function getIdByURL($url) {

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