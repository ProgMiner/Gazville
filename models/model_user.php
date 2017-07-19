<?

class Model_User extends Model{

    protected static $default_data = array(
            'first_name'    => "",
            'last_name'    => "",
            'sidebar'    => "111111111"
        );

    private $keychain;

    public function __construct(Keychain $keychain){

        $this->keychain = $keychain;
    }

    public function getId(){

        return $this->keychain->getId();
    }

    public function getKeychain(){

        return $this->keychain;
    }

    public function getData(){

        if(!$this->changed) return $this->data;
        $id = $this->getId();

        $ret = self::$default_data;

        // Getting general data

        $ret = array_replace($ret, self::getPublicData($id));

        // Getting meta-data

        $stmt = db()->prepare("SELECT `field`, `value`, `key`, `hash` FROM `meta` WHERE (`owner` = ? AND `type` = 'user')")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("i", $id) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        
        while($row = $result->fetch_assoc())
            if(($ret[$row['field']] = $this->keychain->decryptData($row['value'], $row['hash'], $row['key'])) === false) unset($ret[$row['field']]);

        if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        // Preparing data

        $ret['full_name'] = $ret['first_name'] . $ret['last_name'];

        $this->data = $ret;
        $this->changed = false;

        return $ret;
    }

    public static function getIdByLogin($login){

        $stmt = db()->prepare("SELECT `id` FROM `users` WHERE `login` = ? LIMIT 1")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("s", $login) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->bind_result($id) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        if(is_null($stmt->fetch())) return false; // Login isn't exists
        if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        return $id;
    }

    public static function getPublicData($id){

        $stmt = db()->prepare("SELECT `login`, `vk_id`, `permissions` FROM `users` WHERE `id` = ? LIMIT 1")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("i", $id) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $ret = $result->fetch_assoc();

        if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        return $ret;
    }
}