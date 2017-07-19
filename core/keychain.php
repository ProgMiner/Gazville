<?

Route::loadModel("Keychain");

class Keychain{

    private $model;
    private $tokenSecret;

    private function __construct($id, $key){

        $this->model = new Model_Keychain($id, $key);
    }

    public function getId(){

        return $this->model->getId();
    }

    public function decryptData($encrypted, $hash, $keyHash){

        $key = $this->model->getData();
        $key = $key[$keyHash];

        openssl_private_decrypt($encrypted, $data, $key) or Util::opensslDie(__FILE__, __LINE__);

        if(md5($data) !== $hash) return false;
        return $data;
    }

    private function getTokenSecret(){

        $secret = "";
        if(!is_null($this->tokenSecret)) $secret = $this->tokenSecret;
        else{

            $key = Model_Keychain::getKey($this->getId(), "session");
            $key = $key['key'];

            $this->tokenSecret = $secret = md5($key);
        }

        return $secret;
    }

    public function generateToken($salt = false){

        if($salt === false) $salt = time();
        $secret = $this->getTokenSecret();

        $token = "{$salt}:" . md5("{$salt}:{$secret}");
        return $token;
    }

    public function checkToken($token){

        $salt = explode(":", $token);
        $salt = $salt[0];

        return $token === $this->generateToken($salt);
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

        if($id !== User::getCurrentUserId()) return;

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

    public static function encryptRSA($source, $key, &$ok = null, $private = false){

        $maxlength = openssl_pkey_get_details($key);
        $maxlength = $maxlength['bits'] / 8 - 11;

        $ret = "";
        do{
            $input = substr($source, 0, $maxlength);
            if(($source = substr($source, $maxlength)) === false) break;

            if($private) $ok = openssl_private_encrypt($input, $encrypted, $key);
            else $ok = openssl_public_encrypt($input, $encrypted, $key);
 
            $ret .= $encrypted;
        }while($ok);

        return $ret;
    }

    public static function decryptRSA($source, $key, &$ok = null, $public = false){

        $maxlength = openssl_pkey_get_details($key);
        $maxlength = $maxlength['bits'] / 8;

        $ret = "";
        do{
            $input = substr($source, 0, $maxlength);
            if(($source = substr($source, $maxlength)) === false) break;

            if($public) $ok = openssl_public_decrypt($input, $decrypted, $key);
            else $ok = openssl_private_decrypt($input, $decrypted, $key);

            $ret .= $decrypted;
        }while($ok);

        return $ret;
    }
}
