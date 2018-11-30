<?php

namespace Engine\Model;

use Engine\Core\Database\Connection;

class QuestionsModel
{
    /**
     * Получает список категорий
     * @return array
     */
    public function getCategories()
    {
        $sth = Connection::get()->connect()->prepare(
               "SELECT `id`,`category` FROM `categories`");
        $sth->execute();
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Соединение таблиц для вывода данных.
     * @param string $status
     * @return array
     */
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
     * Апдейт статуса вопроса 0-открыт 1-скрыт
     * @param array $param
     */
    public function actionStatusQuestion($param)
    {
        $sth = Connection::get()->connect()->prepare(
                "UPDATE questions SET status = ? WHERE id = ?");
        $sth->execute($param);
    }
    
    /**
     * Удаляет категорию и все вопросы в ней
     * @param array $param
     */
    public function actionDelCategory($param)
    {
        $connect = Connection::get()->connect();
        $sth = $connect->prepare(
                "DELETE FROM questions WHERE category_id=?");
        $sth->execute($param);
        $sth1 = $connect->prepare(
                "DELETE FROM categories WHERE id=? LIMIT 1");
        $sth1->execute($param);
    }
    
    /**
     * Добавляет новую категорию
     * @param array $param
     */
    public function actionAddCategory($param)
    {
        $sth = Connection::get()->connect()->prepare(
                "INSERT INTO categories (category) VALUES ( ? )");
        $sth->execute($param);
    }
    
    /**
     * Удаляет вопрос
     * @param array $param
     */
    public function actionDelQuestion($param)
    {
        $sth = Connection::get()->connect()->prepare(
                "DELETE FROM questions WHERE id=?");
        $sth->execute($param);
    }
    
    /**
     * Изменяет вопрос
     * @param array $param
     */
    public function actionChangeQuestion($param)
    {
        $sth = Connection::get()->connect()->prepare(
                "UPDATE questions SET question = ? WHERE id = ?");
        $sth->execute($param);
    }
    
    /**
     * Проверяет существование ответа на вопрос, возвращает id ответа
     * @param array $param
     * @return array
     */
    public function checkAnswer($param)
    {
        $sth = Connection::get()->connect()->prepare(
                "SELECT answer_id FROM questions WHERE id= ?");
        $sth->execute($param);
        return $sth->fetch();
    }

    /**
     * Изменение ответа
     * @param array $param
     */
    public function actionChangeAnswer($param)
    {
        $sth = Connection::get()->connect()->prepare(
                "UPDATE answers SET answer = :answer, admin_id = :admin_id WHERE id = :id");
        $sth->execute($param);
    }
    
    /**
     * Добавление нового ответа, возвращает id созданного ответа
     * @param array $param
     * @return array
     */
    public function addAnswer($param)
    {
        $connect = Connection::get()->connect();
        $sth = $connect->prepare(
                "INSERT INTO answers (answer, admin_id) VALUES (:answer, :admin_id)");
        $sth->execute($param);
        $id = $connect->lastInsertId();
        return $id;
    }
    
    /**
     * Обновляет id ответа в вопросе
     * @param array $param
     */
    public function updateAnswerId($param)
    {
        $sth = Connection::get()->connect()->prepare(
                "UPDATE questions SET answer_id = ? WHERE id = ?");
        $sth->execute($param);
    }

    /**
     * Обновляет категорию вопроса
     * @param array $param
     */
    public function actionUpdateCategory($param)
    {
        $sth = Connection::get()->connect()->prepare(
                "UPDATE questions SET category_id = ? WHERE id = ?");
        $sth->execute($param);
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
