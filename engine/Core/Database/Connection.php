<?php

namespace Engine\Core\Database;

use \PDO;

//Устанавливате соединение и выполняет запросы

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
        $config = require __DIR__ . '/../../Config/Database.php';
        
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
    
//    public function execute($sql)
//    {
//        $sth = $this->connect()->prepare($sql);
//        return $sth->execute();
//    }
//    
//    public function query($sql) {
//        $sth = $this->connect()->prepare($sql);
//        $sth->execute();
//        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
//        
//        if (!$result) {
//            return [];
//        }
//        
//        return $result;
//    }

}
