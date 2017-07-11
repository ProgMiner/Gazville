<?

class DBSingleton{

    private static $mysqli;

    public static function getMySQLi(){

        if(is_null(self::$mysqli)){

            self::$mysqli = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME);

            if (self::$mysqli->connect_error)
                die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);

            self::$mysqli->query("set character_set_client='cp1251'"); 
            self::$mysqli->query("set character_set_results='cp1251'"); 
            self::$mysqli->query("set collation_connection='cp1251_general_ci'");

        }
        return self::$mysqli;
    }
}

function db(){

    return DBSingleton::getMySQLi();
}
