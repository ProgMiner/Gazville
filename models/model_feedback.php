<?
class Model_Feedback extends Model{

    public function getData($all = false){

        if(!User::isUserLoggedIn()) return false;

        if(!$this->changed) return $this->data;

        $ret = self::$default_data;

        $stmt = db()->prepare("SELECT `title`, `content` FROM `feedback` WHERE `author` = ?". ($all ? "" : "ORDER BY `id` DESC LIMIT 5"))
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

    public function send(){

    }

    public function receive(){


    }
}