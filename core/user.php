<?

include_once(strtolower(Route::$path['model'] . Route::$prefix['model'] . "User.php"));

class User{

    public static $openssl_aes = "AES-256-OFB";
    public static $openssl_config = array(
            'private_key_bits'    => 2048,
            'private_key_type'    => OPENSSL_KEYTYPE_RSA,
            'encrypt_key'         => false
        );

    private static $current_user;

    private $model;

    private function __construct($id, $keychain){

        $this->module = new Model_User($id, $keychain);
    }

    public function logout(){

        //
    }

    public static function login($login, $password_hash){

        $id = Model_User::getId($login);
        if($id === false) return 1; // Incorrect login

        $keychain = Keychain::getKeychain($id, $password_hash);
        if($keychain === false) return 2; // Incorrect password

        self::$current_user = new User($id, $keychain);

        return 0; //OK
    }

    public static function isUserLoggedIn(){
        return !is_null(self::$current_user);
    }

    public static function getCurrentUser(){
        return self::$current_user;
    }
}