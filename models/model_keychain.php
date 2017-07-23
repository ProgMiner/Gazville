<?

class Model_Keychain extends Model{

    private $id;
    private $key;
    private $keyHash;

    public function __construct($id, $key){

        parent::__construct();

        $this->id = $id;
        $this->key = $key;

        openssl_pkey_export($key, $pem, "", User::$openssl_config) or Util::opensslDie(__FILE__, __LINE__);
        $this->keyHash = md5($pem);
    }

    public function getId(){

        return $this->id;
    }

    private function decryptKey($key, $hash){

        $pem = Keychain::decryptRSA($key, $this->key, $ok);
        if(!$ok) Util::opensslDie(__FILE__, __LINE__);

        if(md5($pem) !== $hash) return false;

        $key = openssl_pkey_get_private($pem) or Util::opensslDie(__FILE__, __LINE__);
        return $key;
    }

    public function getData(){

        if(!is_null($this->data)) return $this->data;

        $ret = array(
                $this->keyHash => $this->key
            );

        $stmt = db()->prepare("SELECT `key_hash`, `key` FROM `keys` WHERE (`user_id` = ? AND `key_type` = 'group')")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("i", $id) or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $id = $this->id;

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        
        while($row = $result->fetch_assoc())
            if(($ret[$row['key_hash']] = $this->decryptKey($row['key'], $row['key_hash'])) === false) unset($ret[$row['key_hash']]);

        if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $this->data = $ret;

        return $ret;
    }

    public function updateSession($code){

        $id = $this->id;
        self::resetSession($id);

        $stmt = db()->prepare("INSERT INTO `keys` (`key_hash`, `key`, `key_type`, `user_id`) VALUES (?, ?, 'session', ?)")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("ssi", $hash, $key, $id) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $key = "";
        $hash = "";
        {
            openssl_pkey_export($this->key, $pem, "", User::$openssl_config)
                or Util::opensslDie(__FILE__, __LINE__);

            $hash = md5($pem);
            $key = openssl_encrypt($pem, User::$openssl_aes, $code, 0, hex2bin($hash))
                or Util::opensslDie(__FILE__, __LINE__);
        }

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);
    }

    public static function resetSession($id){

        $stmt = db()->prepare("DELETE FROM `keys` WHERE (`user_id` = ? AND `key_type` = 'session') LIMIT 1")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("i", $id) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);
    }

    public static function getKeyByHash($owner, $hash, $type = "group"){

        $stmt = db()->prepare("SELECT `key` FROM `keys` WHERE (`user_id` = ? AND `key_type` = ? AND `key_hash` = ?) LIMIT 1")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("iss", $owner, $type, $hash) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->bind_param($key) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        if(is_null($stmt->fetch())) return false; // Key isn't exists
        if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        return $key;
    }

    public static function getKey($owner, $type = "user", $hash = NULL){

        if(!is_null($hash) || $type === "group") return array(
                'key' => getKeyByHash($owner, $hash, $type),
                'key_hash' => $hash
            );

        $stmt = db()->prepare("SELECT `key_hash`, `key` FROM `keys` WHERE (`user_id` = ? AND `key_type` = ?) LIMIT 1")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("is", $owner, $type) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $ret = $result->fetch_assoc();

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        return $ret;
    }
}