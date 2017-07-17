<?

// Configuration
define("DEBUG", true);
require_once("config.php");

ini_set("display_errors", DEBUG ? 1 : 0);
error_reporting(DEBUG ? E_ALL : 0);

// Core files
require_once("core/controller.php");
require_once("core/model.php");
require_once("core/view.php");
require_once("core/db.php");
require_once("core/util.php");
require_once("core/route.php");
require_once("core/keychain.php");
require_once("core/user.php");

/*
Здесь подключаются дополнительные модули, реализующие различный функционал:
	> аутентификацию
	> кеширование
	> работу с формами
	> абстракции для доступа к данным
	> ORM
	> Unit тестирование
	> Benchmarking
	> Работу с изображениями
	> Backup
	> и др.
*/

/*
// Generating and encrypting user's key

$key = openssl_pkey_new(User::$openssl_config);
var_dump(openssl_pkey_export($key, $pem, "", User::$openssl_config));
echo(($hash = md5($pem)) . "\n");

$pem = openssl_encrypt($pem, User::$openssl_aes, md5(TEST_PASSWORD), 0, hex2bin($hash));

echo($pem);
*/

User::start(); // Check authorization
Route::start(); // Start routing