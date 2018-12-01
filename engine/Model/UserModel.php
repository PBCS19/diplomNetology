<?php

namespace Engine\Model;

use Engine\Core\ParentModel\Model;
use Engine\Core\Database\Connection;

class UserModel extends Model
{ 
    /**
     * Добавляет нового юзера и возвращает его id
     * @param array $param
     * @return string
     */
    public function addUser($param)
    {
        return $this->prepare("INSERT INTO users (name, email) VALUES (:name, :email)",$param);
    }
    
    /**
     * Проверяет существование юзера, возвращает его id
     * @param array $param
     * @return array
     */
    public function getIdUser($param)
    {
        $rez = $this->prepare("SELECT `id` FROM `users` WHERE name=:name AND email=:email",$param);
        return array_shift($rez);
    }
    
    /**
     * Проверяет юзера на существование, возвращает его id
     * @param array $param
     * @return array
     */
    public function checkUser($param)
    {
        $rez = $this->prepare("SELECT user_id FROM questions WHERE id= ?",$param);
        return array_shift($rez);
    }
    
    /**
     * Изменение имени юзера
     * @param array $param
     */
    public function actionChangeName($param)
    {
        $this->prepare("UPDATE users SET name = ? WHERE id = ?",$param);
    }
}
