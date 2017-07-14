<?

include_once(strtolower(Route::$path['model'] . Route::$prefix['model'] . "Keychain.php"));

class Keychain{

    private $model;

    private function __construct($id, $key){

        $this->module = new Model_Keychain($id, $key);
    }

    public static function getKeychain($id, $password_hash){

        $key = Model_Keychain::getKey($id);

        if(is_null($key)) return false;

        $hash = $key['hash'];
        $key = $key['key'];

        $key = openssl_decrypt($key, User::$openssl_aes, $password_hash, 0, hex2bin($hash))
            or Util::opensslDie(__FILE__, __LINE__);

        if(md5($key) !== $hash) return false; // Incorrect password

        $key = openssl_pkey_get_private($key) or Util::opensslDie(__FILE__, __LINE__);

        return new Keychain($id, $key);
    }
}