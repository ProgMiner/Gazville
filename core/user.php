<?

Route::loadModel("User");

class User{

    public static $openssl_aes = "AES-256-OFB";
    public static $openssl_config = array(
            'private_key_bits'    => 2048,
            'private_key_type'    => OPENSSL_KEYTYPE_RSA,
            'encrypt_key'         => false
        );

    public static $cookie_name = array(
            'session_id'    => "session_id",
            'session_code'  => "session_code"
        );

    public static $permission = array(
            'login'         => 1,
            'adminpanel'    => 2
        );

    private static $current_user;

    private $model;

    private function __construct($keychain){

        $this->model = new Model_User($keychain);
    }

    public function getId(){

        return $this->model->getId();
    }

    public function getKeychain(){

        return $this->model->getKeychain();
    }

    public function getData($fields = false){

        $data = $this->model->getData();

        if($fields === false) return $data;
        if(is_string($fields)) return $data[$fields];
        if(!is_array($fields)) return false;

        $ret = array();
        foreach($fields as $key)
            $ret[$key] = $data[$key];

        return $ret;
    }

    public function isUserCan($permission){

        $permissions = $this->model->getData();
        $permissions = $permissions['permissions'];

        return ($permissions & $permission) !== 0;
    }

    public static function logout($id = false){
        
        if($id === false) $id = self::$current_user->getId();
        Keychain::resetSession($id);
    }

    public static function login($login, $password_hash, $remember = false){

        $id = Model_User::getIdByLogin($login);
        if($id === false) return 1; // Incorrect login

        $keychain = Keychain::getKeychain($id, $password_hash);
        if($keychain === false) return 2; // Incorrect password

        self::$current_user = new User($keychain);
        self::$current_user->model->getKeychain()->updateSession($remember);

        return 0; //OK
    }

    public static function isUserLoggedIn(){

        return !is_null(self::$current_user);
    }

    public static function getCurrentUser(){

        return self::$current_user;
    }

    public static function getCurrentUserId(){

        if(!self::isUserLoggedIn()) return 0;
        return self::$current_user->getId();
    }

    public static function getCurrentUserData($fields = false){

        if(!self::isUserLoggedIn()) return array();
        return self::$current_user->getData($fields);
    }

    public static function isCurrentUserCan($permission){

        if(!self::isUserLoggedIn()) return false;
        return self::$current_user->isUserCan($permission);
    }

    public static function start(){

        $keychain = Keychain::getKeychainBySession();
        if($keychain === false) return; // Incorrect password

        self::$current_user = new User($keychain);
    }
}
