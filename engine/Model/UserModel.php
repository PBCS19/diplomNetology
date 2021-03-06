<?php

namespace Engine\Model;

use Engine\Core\ParentModel\Model;

class UserModel extends Model
{ 
    /**
     * Добавляет нового юзера и возвращает его id
     * @param array $param
     * @return string
     */
    public function addUser($param)
    {
        return $this->lastInsertId(
                "INSERT INTO users (name, email) VALUES (:name, :email)",
                $param);
    }
    
    /**
     * Проверяет существование юзера, возвращает его id
     * @param array $param
     * @return array
     */
    public function getIdUser($param)
    {
        return $this->prepareFetch(
                "SELECT `id` FROM `users` WHERE name=:name AND email=:email",
                $param);
    }
    
    /**
     * Возвращает id юзера из таблицы с вопросами
     * @param array $param
     * @return array
     */
    public function checkUser($param)
    {
        return $this->prepareFetch("SELECT user_id FROM questions WHERE id= ?", $param);
    }
    
    /**
     * Изменение имени юзера
     * @param array $param
     */
    public function actionChangeName($param)
    {
        $this->prepare("UPDATE users SET name = ? WHERE id = ?", $param);
    }
}
