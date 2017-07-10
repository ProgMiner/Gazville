<?

ini_set("display_errors", 1);

// Конфигурация
define("DEBUG", true);
require_once("config.php");

// Подключаем файлы ядра
require_once("core/db.php");
require_once("core/util.php");
require_once("core/model.php");
require_once("core/controller.php");
require_once("core/view.php");
require_once("core/route.php");

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

Route::start(); // Запускаем маршрутизатор