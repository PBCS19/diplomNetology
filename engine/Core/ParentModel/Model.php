<?php

namespace Engine\Core\ParentModel;

use Engine\Core\Database\Connection;

class Model 
{
    public function prepare($sql, $param)
    {
        $connection = Connection::get()->connect();
        $sth = $connection->prepare($sql);
        return $sth->execute($param);
    }
    
    public function prepareFetchAll($sql, $param)
    {
        $connection = Connection::get()->connect();
        $sth = $connection->prepare($sql);
        $sth->execute($param);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function prepareFetch($sql, $param)
    {
        $connection = Connection::get()->connect();
        $sth = $connection->prepare($sql);
        $sth->execute($param);
        return $sth->fetch();
    }
    
    /**
     * Получение id после INSERT INTO
     * @param string $sql
     * @param array $param
     * @return string
     */
    public function lastInsertId($sql, $param)
    {
        $connection = Connection::get()->connect();
        $sth = $connection->prepare($sql);
        $sth->execute($param);
        $sth2 = $connection->prepare('SELECT LAST_INSERT_ID()');
        $sth2->execute();
        return $sth2->fetchColumn();
    }
}
