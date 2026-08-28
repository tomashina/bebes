<?php
class NitroDb {
    public static $singleton;
    public static $created_nitro_product_cache = false;
    private $link = false;
    
    public static function getInstance() {
        if (empty(self::$singleton)) self::$singleton = new NitroDb(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        return self::$singleton->getLink();
    }
    
    public static function getInstanceWithParams($adaptor, $hostname, $username, $password, $database, $port = NULL) {
        if (empty(self::$singleton)) self::$singleton = new NitroDb($adaptor, $hostname, $username, $password, $database, $port);
        return self::$singleton->getLink();
    }
    
    public function __construct($adaptor, $hostname, $username, $password, $database, $port = NULL) {
        $class = 'DB\\' . $adaptor;

        if (!class_exists($class)) {
            $file = DIR_SYSTEM . 'library' . DIRECTORY_SEPARATOR . str_replace('\\', '/', strtolower($class)) . '.php';

            if (file_exists($file)) {
                require_once $file;
            }
        }

        if (class_exists($class)) {
            $this->link = new $class($hostname, $username, $password, $database, $port);
        }
    }
    
    public function getLink() { return $this->link; }
}
