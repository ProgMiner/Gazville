<?

class Model_Keychain extends Model{

    private $id;
    private $key;
    private $keyHash;

    public function __construct($id, $key){

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

        if(!$this->changed) return $this->data;

        $ret = array(
                $this->keyHash => $this->key
            );

        $stmt = db()->prepare("SELECT `hash`, `key` FROM `keys` WHERE (`owner` = ? AND `type` = 'group')")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("i", $id) or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $id = $this->id;

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        
        while($row = $result->fetch_assoc())
            if(($ret[$row['hash']] = $this->decryptKey($row['key'], $row['hash'])) === false) unset($ret[$row['hash']]);

        if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $this->data = $ret;
        $this->changed = false;

        return $ret;
    }

    public function updateSession($code){

        $id = $this->id;
        self::resetSession($id);

        $stmt = db()->prepare("INSERT INTO `keys` (`hash`, `key`, `type`, `owner`) VALUES (?, ?, 'session', ?)")
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

        $stmt = db()->prepare("DELETE FROM `keys` WHERE (`owner` = ? AND `type` = 'session') LIMIT 1")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("i", $id) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);
    }

    public static function getKeyByHash($owner, $hash, $type = "group"){

        $stmt = db()->prepare("SELECT `key` FROM `keys` WHERE (`owner` = ? AND `type` = ? AND `hash` = ?) LIMIT 1")
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
                'hash' => $hash
            );

        $stmt = db()->prepare("SELECT `hash`, `key` FROM `keys` WHERE (`owner` = ? AND `type` = ?) LIMIT 1")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("is", $owner, $type) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $ret = $result->fetch_assoc();

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        return $ret;
    }
}