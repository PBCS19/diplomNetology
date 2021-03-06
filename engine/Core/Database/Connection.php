<?php

namespace Engine\Core\Database;

use \PDO;

/**
 * Устанавливате соединение и выполняет запросы
 */
class Connection 
{   
    static $di = null;
    
    public static function get()
    {
        if (! self::$di) {
            self::$di = new Connection();
        }
        return self::$di;
    }
    
    public function connect()
    {
        $config = require DATABASE_CONFIG;
        
        try {
            $db = new PDO(
                'mysql:host='.$config['host'].';dbname='.$config['dbname'].';charset='.$config['charset'],
                $config['username'],
                $config['password']
            );
        } catch (PDOException $e) {
            die('Database error: '.$e->getMessage().'<br/>');
        }
        return $db;
    }

}
