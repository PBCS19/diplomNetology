<?php

namespace Engine\Core\ParentModel;

use Engine\Core\Database\Connection;

class Model 
{
    public function getCategories()
    {
        $sth = Connection::get()->connect()->prepare(
               "SELECT `id`,`category` FROM `categories`");
        $sth->execute();
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getQuestionCategories($status)
    {
        if ($status === 'all') {
            $param = 'ORDER BY q.date DESC';
            $status = [];
        } elseif ($status === 'status') {
            $param = 'WHERE q.answer_id IS NOT NULL AND q.status = 0';
            $status = [];
        } elseif ($status === 'onlyNoAnswer') {
           $param = 'WHERE q.answer_id IS NULL ORDER BY q.date DESC';
           $status = [];
        } else {
            $param = 'WHERE q.id = ?';
        }
        $sth = Connection::get()->connect()->prepare(
            "SELECT q.id,question,status,category_id,category,answer_id,q.date,name,email,answer,user_id "
                    . "FROM questions q "
                    . "INNER JOIN categories c ON c.id=q.category_id "
                    . "LEFT JOIN users u ON u.id=q.user_id "
                    . "LEFT JOIN answers a ON a.id=q.answer_id "
                    . $param);
        $sth->execute($status);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }
}
