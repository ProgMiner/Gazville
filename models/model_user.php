<?

Route::loadModel("Meta");

class Model_User extends Model{

    protected static $default_data = array(
            'first_name'    => "",
            'last_name'     => "",
            'sidebar'       => 511
        );

    private $id;
    private $meta;
    private $keychain;

    public function __construct(Keychain $keychain, $id = null){

        if(is_null($id)) $id = $keychain->getId();

        $this->id = $id;
        $this->keychain = $keychain;
        $this->meta = new Model_Meta($id, "user", $keychain);
    }

    public function getId(){

        return $this->id;
    }

    public function getKeychain(){

        return $this->keychain;
    }

    public function getData(&$key_hash = null){

        if(!$this->changed) return $this->data;
        $id = $this->getId();

        $key_hash = array();
        $ret = self::$default_data;

        { // Getting general data
    
            $stmt = db()->prepare("SELECT `user_login`, `vk_id` FROM `users` WHERE `user_id` = ? LIMIT 1")
                or Util::mysqlDie(db(), __FILE__, __LINE__);
    
            $stmt->bind_param("i", $id) or Util::mysqlDie($stmt, __FILE__, __LINE__);
    
            $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);
            $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);
            $ret = array_replace($ret, $result->fetch_assoc());
    
            if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);
    
            $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        
        }

        $ret = array_replace($ret, $this->meta->getData());

        // Preparing data

        $ret['full_name'] = $ret['first_name'] . $ret['last_name'];

        $this->data = $ret;
        $this->changed = false;

        return $ret;
    }

    public function setData($field, $key_hash){

        $id = $this->id;
        $field = array_replace(self::getData($default_key_hash), $field);
        $key_hash = array_replace($default_key_hash, $key_hash);

        { // Setting general data

            $stmt = db()->prepare("UPDATE `users` SET `user_login` = ?, `vk_id` = ? WHERE `user_id` = ? LIMIT 1")
                or Util::mysqlDie(db(), __FILE__, __LINE__);

            $stmt->bind_param("sii", $login, $vk, $id) or Util::mysqlDie($stmt, __FILE__, __LINE__);
            $login = $field['login'];
            $vk = $field['vk_id'];

            $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);

            $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        }do{ // Setting meta-data

            $hash = array();
            foreach($key_hash as $name => $key){

                $value = $field[$name];
                $hash[$name] = md5($value);
                $field[$name] = $this->keychain->encryptData($value, $key);
            }

            $query = "INSERT IGNORE INTO `meta` (`field`, `value`, `key`, `hash`, `type`, `owner`) VALUES";
            foreach($key_hash as $name => $key)
                $query .= " ('{$name}', '{$field[$name]}', '{$key}', '{$hash[$name]}', 'user', {$id}),";

            $query = rtrim($query, ",");
            db()->query($query) or Util::mysqlDie(db(), __FILE__, __LINE__);
            if(db()->affected_rows === count($key_hash)) break;

            $query = "UPDATE IGNORE `meta` SET
                `value` = CASE %s END,
                `key` = CASE %s END,
                `hash` = CASE %s END
                WHERE (`type` = 'user' AND `owner` = {$id} AND `field` IN (%s))";

            foreach($key_hash as $name => $key)
                $query = sprintf($query,
                    "WHEN `field` = '{$name}' THEN '{$field[$name]}' %s",
                    "WHEN `field` = '{$name}' THEN '{$key}' %s",
                    "WHEN `field` = '{$name}' THEN '{$hash[$name]}' %s",
                    "'{$name}', %s");
            $query = str_replace(", %s", "", $query);
            $query = str_replace(" %s", "", $query);
            db()->query($query) or Util::mysqlDie(db(), __FILE__, __LINE__);
        }while(false);

        $this->changed = true;

        return true;
    }

    public static function getIdByLogin($login){

        $stmt = db()->prepare("SELECT `user_id` FROM `users` WHERE `user_login` = ? LIMIT 1")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("s", $login) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->bind_result($id) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        if(is_null($stmt->fetch())) return false; // Login isn't exists
        if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        return $id;
    }

    public static function getLoginById($id){

        $stmt = db()->prepare("SELECT `user_login` FROM `users` WHERE `user_id` = ? LIMIT 1")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("i", $id) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->bind_result($login) or Util::mysqlDie($stmt, __FILE__, __LINE__);

        if(is_null($stmt->fetch())) return false; // ID isn't exists
        if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        return $login;
    }
}