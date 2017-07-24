<?

class Model_Feedback extends Model{

    private $all = false;
    private $id;

    public function __construct($all = false, $id = null){

        $this->all = $all;
        $this->id = $id;
    }

    private function getMessage($id){

        if(!is_null($this->data) && isset($this->data[$id])) return $this->data[$id];

        $stmt = db()->prepare("SELECT `msg_id`, `msg_time`, `msg_subject`, `msg_content`, `msg_hash` FROM `feedback` WHERE (`msg_id` = ? AND (`msg_author` = ? OR `msg_author` = ?))")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("iss", $id, $user_id, $author) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $user_id = User::getCurrentUserId();
        if(empty($author = User::getCurrentUserData("email"))) $author = $id;

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $ret = $result->fetch_assoc();

        if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        if(count($ret) > 0) $ret['msg_content'] = User::getCurrentUser()->getKeychain()->decryptData($ret['msg_content'], $ret['msg_hash']);

        return $ret;
    }

    public function getData(){

        if(!User::isUserLoggedIn()) return array();

        if(!is_null($this->id)) return $this->getMessage($this->id);
        if(!is_null($this->data)) return $this->data;

        User::getCurrentUser()->getKeychain()->getKeys();

        $ret = array();

        $stmt = db()->prepare("SELECT `msg_id`, `msg_time`, `msg_subject`, `msg_content`, `msg_hash` FROM `feedback` WHERE (`msg_author` = ? OR `msg_author` = ?)" .
            ($this->all ? "" : " ORDER BY `msg_id` DESC LIMIT 5")) or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("ss", $id, $author) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $id = User::getCurrentUserId();
        if(empty($author = User::getCurrentUserData("email"))) $author = $id;

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        while($row = $result->fetch_assoc()){

            $row['msg_content'] = User::getCurrentUser()->getKeychain()->decryptData($row['msg_content'], $row['msg_hash']);
            $ret[$row['msg_id']] = $row;
        }

        if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $this->data = $ret;

        return $ret;
    }

    public static function sendMessage($subject, $content, $author = null){

        if(is_null($author))
            if(!User::isUserLoggedIn()) return false;
            elseif(empty($author = User::getCurrentUserData("email"))) $author = User::getCurrentUserId();

        $stmt = db()->prepare("INSERT INTO `feedback` (`msg_subject`, `msg_content`, `msg_hash`, `msg_author`) VALUES (?, ?, ?, ?)")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("ssss", $subject, $content, $hash, $author) or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $hash = md5($content);

        $key = file_get_contents("key.pem");
        $key = openssl_pkey_get_public($key);
        $content = Keychain::encryptRSA($content, $key, $ok);
        if(!$ok) return false;

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        return true;
    }
}