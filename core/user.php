<?

include(strtolower(Route::$path['model'] . Route::$prefix['model'] . "User.php"));

class User extends Model_User{

    public static $openssl_aes = "AES_256_OFB";
}