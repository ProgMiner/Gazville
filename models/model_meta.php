<?

class Model_Meta extends Model{

    private $keychain;
    private $owner;
    private $type;

    public function __construct($owner, $type, $keychain){

        $this->keychain = $keychain;
        $this->owner = $owner;
        $this->type = $type;

        $this->data = array();
    }

    public function getKeys(){

        if(!$this->changed) return array_values(array_unique(array_keys($this->data)));

        $stmt = db()->prepare("SELECT `key_hash` FROM `meta` WHERE (`owner_id` = ? AND `owner_type` = ?) GROUP BY `key_hash`")
        or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("is", $owner, $type) or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $owner = $this->owner;
        $type = $this->type;

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $ret = array();

        while($row = $result->fetch_assoc())
            $ret[count($ret)] = $row['key'];

        if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        return $ret;
    }

    private function getDataByKey($key){

        $stmt = db()->prepare("SELECT `meta_value`, `meta_hash` FROM `meta` WHERE (`key_hash` = ? AND `owner_id` = ? AND `owner_type` = ?) LIMIT 1")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("sis", $key, $owner, $type) or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $owner = $this->owner;
        $type = $this->type;

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $ret = $result->fetch_assoc();
        if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        if(($ret = $this->keychain->decryptData($ret['meta_value'], $ret['meta_hash'], $key)) === false) return array();

        return json_decode($ret, true);
    }

    private function extractData($data = null){

        if(is_null($data)) $data = $this->data;

        $ret = array();
        foreach($data as $group)
            $ret = array_replace($ret, $group);

        return $ret;
    }

    public function getData(){

        if(!$this->changed) return $this->extractData();

        $keys = $this->getKeys();
        $ret = array();

        foreach($keys as $key)
            $ret[$key] = $this->getDataByKey($key);

        $this->data = $ret;
        $this->changed = false;

        return $this->extractData();
    }
}