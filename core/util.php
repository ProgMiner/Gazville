<?

abstract class Util{

    public static function getRealIPAddress(){

        if(!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP']; // check ip from share internet
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; // to check ip is pass from proxy
        else $ip = $_SERVER['REMOTE_ADDR'];
    
        return $ip;
    }

    public static function ip2int($ip){

        $ip = explode(".", $ip);
        $ret = 0;

        for($i = 0; $i < 4; $i++)
            $ret += intval($ip[$i]) * pow(256, 3 - $i);

        return $ret;
    }

    public static function log($msgarg, $filearg = __FILE__, $linearg = __LINE__){

        $stmt = db()->prepare("INSERT INTO `log` (`msg`, `ip`, `file`, `url`) VALUES (?, ?, ?, ?)")
            or Util::mysqlDie(db(), __FILE__, __LINE__);

        $stmt->bind_param("siss", $msg, $ip, $file, $url)
            or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $msg = $msgarg;
        $ip = Util::ip2int(Util::getRealIPAddress());
        $file = "{$filearg}:{$linearg}";
        $url = $_SERVER['REQUEST_URI'];

        $stmt->execute() or Util::mysqlDie($stmt, __FILE__, __LINE__);

        $stmt->close() or Util::mysqlDie($stmt, __FILE__, __LINE__);
    }

    public static function _die($msg, $file = __FILE__, $line = __LINE__, $log = true){

        if($log) Util::log($msg, $file, $line);

        if(DEBUG) die("An error <b>" . $msg . "</b> occured in file <b>" . $file . "</b> on line <b>" . $line . "</b>.");
        else die("An error occured.");
    }

    public static function mysqlDie($mysqli, $file = __FILE__, $line = __LINE__){

        Util::_die("MySQL Error({$mysqli->errno}): {$mysqli->error}", $file, $line, false);
    }

    public static function opensslDie($file = __FILE__, $line = __LINE__){

        Util::_die("OpenSSL " . openssl_error_string(), $file, $line);
    }
}