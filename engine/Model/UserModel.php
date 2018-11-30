<?php

namespace Engine\Model;

use Engine\Core\Database\Connection;

class UserModel
{ 
    /**
     * Добавляет нового юзера
     * @param array $param
     */
    public function addUser($param)
    {
        $sth = Connection::get()->connect()->prepare(
               "INSERT INTO users (name, email) VALUES (:name, :email)");
        $sth->execute($param);
    }
    
    /**
     * Проверяет существование юзера, возвращает его id
     * @param array $param
     * @return array
     */
    public function getIdUser($param)
    {
        $sth = Connection::get()->connect()->prepare(
               "SELECT `id` FROM `users` WHERE name=:name AND email=:email");
        $sth->execute($param);
        return $sth->fetch();
    }
    
    /**
     * Проверяет юзера на существование, возвращает его id
     * @param array $param
     * @return array
     */
    public function checkUser($param)
    {
        $sth = Connection::get()->connect()->prepare(
                "SELECT user_id FROM questions WHERE id= ?");
        $sth->execute($param);
        return $sth->fetch();
    }
    
    /**
     * Изменение имени юзера
     * @param array $param
     */
    public function actionChangeName($param)
    {
        $sth = Connection::get()->connect()->prepare(
                "UPDATE users SET name = ? WHERE id = ?");
        $sth->execute($param);
    }
}
