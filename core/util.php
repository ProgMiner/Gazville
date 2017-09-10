<?php

abstract class Util{
    
    public static function sendCookie($name, $value = "", $duration = 0, $path = "/", $domain = false) {
        
        if($domain === false) $domain = ($_SERVER['HTTP_HOST'] !== "localhost") ? $_SERVER['HTTP_HOST'] : false;
        
        if($duration === false) $duration = 1;
        else if($duration !== 0) $duration += time();
        
        setcookie($name, $value, $duration, $path, $domain, false, true);
    }
    
    public static function error404($file = __FILE__, $line = __LINE__) {
        
        header("HTTP/1.1 404 Not Found");
        header("Status: 404 Not Found");
        header("Location: /404");
        
        Util::_die("404 Not Found", $file, $line);
    }
    
    public static function redirect($location, $file = __FILE__, $line = __LINE__) {
        
        header("HTTP/1.1 302 Moved Temporarily");
        header("Status: 302 Moved Temporarily");
        header("Location: {$location}");
        
        Util::_die("302 Moved Temporarily to {$location}", $file, $line);
    }

    private static $less = null;

    public static function insertLESS($name, $path = "assets/style/", $anticache = DEBUG, $force = DEBUG, $file = __FILE__, $line = __LINE__) {

        if(is_null(self::$less)) self::$less = new lessc;

        $name = preg_replace("/([\s\S]*?)(\.less)?$/i", "$1", $name);
        $ret = "{$path}{$name}.css";

        try {

            if($force) self::$less->compileFile("{$path}{$name}.less", $ret);
            else self::$less->checkedCompile("{$path}{$name}.less", $ret);
        } catch(exception $e) {

            self::_die("LESS Error: " . $e->getMessage(), $file, $line);
        }

        if($anticache) $ret .= "?" . time();
        return $ret;
    }
    
    public static function getRealIPAddress() {
        
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP']; // check ip from share internet
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; // to check ip is pass from proxy
        else $ip = $_SERVER['REMOTE_ADDR'];
        
        return $ip;
    }
    
    public static function ip2int($ip) {
        
        $ip = explode(".", $ip);
        $ret = 0;
        
        for($i = 0; $i < 4; $i++)
            $ret += intval($ip[$i]) * pow(256, 3 - $i);
        
        return $ret;
    }
    
    public static function log($msgarg, $filearg = __FILE__, $linearg = __LINE__) {
        
        $stmt = db()->prepare("INSERT INTO `log` (`msg_text`, `msg_ip`, `msg_file`, `msg_url`) VALUES (?, ?, ?, ?)")
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
    
    public static function _die($msg, $file = __FILE__, $line = __LINE__, $log = true) {
        
        if($log) Util::log($msg, $file, $line);
        
        if(DEBUG) die("An error <b>" . $msg . "</b> occured in file <b>" . $file . "</b> on line <b>" . $line . "</b>.");
        else die("An error occured.");
    }
    
    public static function mysqlDie($mysqli, $file = __FILE__, $line = __LINE__) {
        
        Util::_die("MySQL Error({$mysqli->errno}): {$mysqli->error}", $file, $line, false);
    }
    
    public static function opensslDie($file = __FILE__, $line = __LINE__) {
        
        Util::_die("OpenSSL " . openssl_error_string(), $file, $line);
    }
}