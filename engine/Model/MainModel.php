<?php

namespace Engine\Model;

use Engine\Core\Database\Connection;
use Engine\Core\ParentModel\Model;

class MainModel extends Model
{
    
    public function addQuestion($param)
    {
        $sth = Connection::get()->connect()->prepare(
               "INSERT INTO questions (question, category_id, user_id) VALUES (:question, :category_id, :user_id)");
        $sth->execute($param);
    }
    
    public function addUser($param)
    {
        $sth = Connection::get()->connect()->prepare(
               "INSERT INTO users (name, email) VALUES (:name, :email)");
        $sth->execute($param);
    }
    
    public function getIdUser($param)
    {
        $sth = Connection::get()->connect()->prepare(
               "SELECT `id` FROM `users` WHERE name=:name AND email=:email");
        $sth->execute($param);
        return $sth->fetch();
    }
    
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
