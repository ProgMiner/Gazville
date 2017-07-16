<?

Route::loadModel("Keychain");

class Keychain{

    private $model;

    private function __construct($id, $key){

        $this->model = new Model_Keychain($id, $key);
    }

    public function getId(){

        return $this->model->getId();
    }

    public function updateSession($remember = false){

        $code = md5(time() . $this->model->getId() . rand());

        $this->model->updateSession($code);

        $domain = ($_SERVER['HTTP_HOST'] !== "localhost") ? $_SERVER['HTTP_HOST'] : false;

        Util::sendCookie(User::$cookie_name['session_id'], $this->model->getId(), time() + 3600 * 24 * 30);
        Util::sendCookie(User::$cookie_name['session_code'], $code, $remember ? (time() + 3600 * 24 * 30) : 0);
    }

    public static function resetSession($id){

        Model_Keychain::resetSession($id);

        if($id !== User::getCurrentUser()->getId()) return;

        $domain = ($_SERVER['HTTP_HOST'] !== "localhost") ? $_SERVER['HTTP_HOST'] : false;
        Util::sendCookie(User::$cookie_name['session_code'], "", false);
    }

    public static function getKeychain($id, $password_hash, $by_session = false){

        $key = Model_Keychain::getKey($id, $by_session ? "session" : "user");

        if(is_null($key)) return false;

        $hash = $key['hash'];
        $key = $key['key'];

        $key = openssl_decrypt($key, User::$openssl_aes, $password_hash, 0, hex2bin($hash))
            or Util::opensslDie(__FILE__, __LINE__);

        if(md5($key) !== $hash) return false; // Incorrect password

        $key = openssl_pkey_get_private($key) or Util::opensslDie(__FILE__, __LINE__);

        return new Keychain($id, $key);
    }

    public static function getKeychainBySession(){

        if(!isset($_COOKIE[User::$cookie_name['session_id']])) return false;
        if(!isset($_COOKIE[User::$cookie_name['session_code']])) return false;

        $id = $_COOKIE[User::$cookie_name['session_id']];
        $code = $_COOKIE[User::$cookie_name['session_code']];

        $keychain = self::getKeychain($id, $code, true);

        if($keychain === false) self::resetSession($id);

        return $keychain;
    }
}