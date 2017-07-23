<?

class Model_Meta extends Model{

    private $keychain;
    private $owner;
    private $type;

    public function __construct($owner, $type, $keychain){

        $this->keychain = $keychain;
        $this->owner = $owner;
        $this->type = $type;
    }

    public function getKeyHash(){

        if(!is_null($this->data)) return array_values(array_unique(array_keys($this->data)));

        $stmt = db()->prepare("SELECT `key_hash` FROM `meta` WHERE (`owner_id` = ? AND `owner_type` = ?) GROUP BY `key_hash`")
        or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("is", $owner, $type) or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $owner = $this->owner;
        $type = $this->type;

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $ret = array();

        while($row = $result->fetch_assoc())
            array_push($ret, $row['key_hash']);

        if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        return $ret;
    }

    public function getData($with_groups = false){

        if(is_null($this->data)){

            $this->keychain->getKeys();

            $data = array();

            $stmt = db()->prepare("SELECT `meta_value`, `meta_hash`, `key_hash` FROM `meta` WHERE (`owner_id` = ? AND `owner_type` = ?)")
                or Util::mysqlDie(db(), __FILE__, __LINE__);

            $stmt->bind_param("is", $owner, $type) or Util::mysqlDie($stmt, __FILE__, __LINE__);
            $owner = $this->owner;
            $type = $this->type;

            $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);

            $stmt->bind_result($value, $hash, $key_hash) or Util::mysqlDie($stmt, __FILE__, __LINE__);

            while($stmt->fetch()){

                $group = "";
                if(($group = $this->keychain->decryptData($value, $hash, $key_hash)) === false) continue;

                $data[$key_hash] = json_decode($group, true);
            }
            if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

            $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

            $this->data = $data;
        }

        if($with_groups) return $this->data;

        $data = array();
        foreach($this->data as $group)
            $data = array_replace($data, $group);

        return $data;
    }

    public function setData($new_data, $new_key_hash = array()){

        $data = $this->getData(true);
        $key_hash = array();

        { // Getting key hashes

            foreach($data as $group_key_hash => $group)
                foreach($group as $field => $value)
                    $key_hash[$field] = $group_key_hash;

            $key_hash = array_replace($key_hash, $new_key_hash);
        }

        // Setting data

        $data = array();
        $new_data = array_replace($this->getData(), $new_data);

        foreach($key_hash as $field => $key_hash){

            if(!isset($data[$key_hash])) $data[$key_hash] = array();
            $data[$key_hash][$field] = $new_data[$field];
        }

        $this->data = $data;
    }

    private function commitGroup($key_hash){

        $stmt = db()->prepare("INSERT INTO `meta` () VALUES (?, ?, ?, ?, ?)")
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

    public function commitData(){

        $data = $this->getData(true);

        { // Remove old data

            $stmt = db()->prepare("DELETE FROM `meta` WHERE (`owner_id` = ? AND `owner_type` = ?)")
                or Util::mysqlDie(db(), __FILE__, __LINE__);

            $stmt->bind_param("is", $owner, $type) or Util::mysqlDie($stmt, __FILE__, __LINE__);
            $owner = $this->owner;
            $type = $this->type;

            $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);

            $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        }{ // Commit data

            if(count($data) <= 0) return;

            $query = "INSERT INTO `meta` (`meta_value`, `meta_hash`, `key_hash`, `owner_type`, `owner_id`) VALUES %s";
            foreach($data as $key_hash => $group){

                $group = json_encode($group);
                $hash = md5($group);

                if(($group = $this->keychain->encryptData($group, $key_hash)) === false) continue;

                $query = sprintf($query, "('{$group}', '{$hash}', '{$key_hash}', '{$this->type}', {$this->owner}), %s");
            }
            $query = str_replace(", %s", "", $query);

            db()->query($query) or Util::mysqlDie(db(), __FILE__, __LINE__);
        }
    }
}