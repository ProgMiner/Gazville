<?

class Model_User extends Model{

    private $keychain;

    public function __construct(Keychain $keychain){

        $this->keychain = $keychain;
    }

    public function getData(){}

    public function getKeychain(){

        return $this->keychain;
    }

    public static function getId($login){

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
}