<?php

namespace Engine\Core\ParentModel;

use Engine\Core\Database\Connection;

class Model 
{
    public function prepare($sql,$param)
    {
        $connection = Connection::get()->connect();
        $sth = $connection->prepare($sql);
        $sth->execute($param);
        if (mb_stripos($sql,'INSERT') !== false) {
            $sth2 = $connection->prepare('SELECT LAST_INSERT_ID()');
            $sth2->execute($param);
            return $sth2->fetchColumn();
        } else {
            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
            if ($result) {
                return $result;
            } else {
                return [];
            }
        }
    }
}
