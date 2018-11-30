<?php

namespace Engine\Model;

use Engine\Core\Database\Connection;
use Engine\Core\ParentModel\Model;

class MainModel extends Model
{
    /**
     * Добавляет новый вопрос
     * @param array $param
     */
    public function addQuestion($param)
    {
        $sth = Connection::get()->connect()->prepare(
               "INSERT INTO questions (question, category_id, user_id) VALUES (:question, :category_id, :user_id)");
        $sth->execute($param);
    }
    
    /**
     * Добавляет нового узера
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
     * Проверяет ошибки при создании нового вопроса юзером
     * @param array $param
     * @return string
     */
    public function checkErrorsQuestions($param)
    {
        $errors = [];
        if (empty($param['name'])) {
            $errors[] = 'Введите ваше имя';
        }
        
        if (empty($param['email'])) {
            $errors[] = 'Введите ваш e-mail';
        }
        
        if (empty($param['question'])) {
            $errors[] = 'Введите вопрос';
        }
        
        return $errors;
    }
}
