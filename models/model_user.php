<?

Route::loadModel("Meta");

class Model_User extends Model{

    protected static $default_data = array(
            'permissions'   => 1,
            'first_name'    => "",
            'last_name'     => "",
            'sidebar'       => 3 // 511
        );

    private $id;
    private $meta;
    private $keychain;

    public function __construct(Keychain $keychain, $id = null){

        parent::__construct();

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

    public function getData(){

        // Getting general data
        if(is_null($this->data)){

            $stmt = db()->prepare("SELECT `user_login`, `vk_id` FROM `users` WHERE `user_id` = ? LIMIT 1")
                or Util::mysqlDie(db(), __FILE__, __LINE__);

            $stmt->bind_param("i", $id) or Util::mysqlDie($stmt, __FILE__, __LINE__);
            $id = $this->getId();

            $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);
            $result = $stmt->get_result() or Util::mysqlDie($stmt, __FILE__, __LINE__);
            $data = $result->fetch_assoc();

            if($stmt->errno !== 0) Util::mysqlDie($stmt, __FILE__, __LINE__);

            $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);

            $this->data = $data;
        }

        // Getting metadata
        $data = array_replace(self::$default_data, $this->data, $this->meta->getData());

        // Preparing data
        if(!isset($data['full_name'])) $data['full_name'] = "{$data['first_name']} {$data['last_name']}";

        return $data;
    }

    public function setData($data, $key_hash = array()){

        $this->getData();

        { // Setting general data
            $data = array_replace($tmp = $this->data, $data);

            foreach($tmp as $key => $value){
                $tmp[$key] = $data[$key];
                unset($data[$key]);
            }

            $this->data = $tmp;
        }

        // Setting metadata
        $this->meta->setData($data, $key_hash);
    }

    public function commitData(){

        { // Commit general data

            $data = $this->getData();

            $stmt = db()->prepare("UPDATE `users` SET `user_login` = ?, `vk_id` = ? WHERE `user_id` = ? LIMIT 1")
                or Util::mysqlDie(db(), __FILE__, __LINE__);

            $stmt->bind_param("sii", $login, $vk, $id) or Util::mysqlDie($stmt, __FILE__, __LINE__);
            $login = $data['user_login'];
            $vk = $data['vk_id'];
            $id = $this->id;

            $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);

            $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);
        }

        // Commit metadata
        $this->meta->commitData();
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