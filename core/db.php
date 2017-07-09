<?

class DBSingleton{

    private static $mysqli;

    static function getMySQLi(){

        if(is_null($self->mysqli)){
            $self->mysqli = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME);

            if ($self->mysqli->connect_error) {
                die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
            }
        }
        return $self->mysqli;

    }

}

function db(){
    return DBSingleton::getMySQLi();
}
