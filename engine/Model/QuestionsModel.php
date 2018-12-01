<?php

namespace Engine\Model;

use Engine\Core\Database\Connection;
use Engine\Core\ParentModel\Model;

class QuestionsModel extends Model
{
    /**
     * Получает список категорий
     * @return array
     */
    public function getCategories()
    {
        return $this->prepare("SELECT `id`,`category` FROM `categories`",[]);
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
        
        return $this->prepare("SELECT q.id,question,status,category_id,category,answer_id,q.date,name,email,answer,user_id "
                    . "FROM questions q "
                    . "INNER JOIN categories c ON c.id=q.category_id "
                    . "LEFT JOIN users u ON u.id=q.user_id "
                    . "LEFT JOIN answers a ON a.id=q.answer_id "
                    . $param,$status);
    }
    
    /**
     * Добавляет новый вопрос
     * @param array $param
     */
    public function addQuestion($param)
    {
        $this->prepare("INSERT INTO questions (question, category_id, user_id) VALUES (:question, :category_id, :user_id)",$param);
    }
    
    /**
     * Апдейт статуса вопроса 0-открыт 1-скрыт
     * @param array $param
     */
    public function actionStatusQuestion($param)
    {
        $this->prepare("UPDATE questions SET status = ? WHERE id = ?",$param);
    }
    
    /**
     * Удаляет категорию и все вопросы в ней
     * @param array $param
     */
    public function actionDelCategory($param)
    {
        $this->prepare("DELETE FROM questions WHERE category_id=?",$param);
        $this->prepare("DELETE FROM categories WHERE id=? LIMIT 1",$param);
    }
    
    /**
     * Добавляет новую категорию
     * @param array $param
     */
    public function actionAddCategory($param)
    {
        $this->prepare("INSERT INTO categories (category) VALUES ( ? )",$param);
    }
    
    /**
     * Удаляет вопрос
     * @param array $param
     */
    public function actionDelQuestion($param)
    {
        $this->prepare("DELETE FROM questions WHERE id=?",$param);
    }
    
    /**
     * Изменяет вопрос
     * @param array $param
     */
    public function actionChangeQuestion($param)
    {
        $this->prepare("UPDATE questions SET question = ? WHERE id = ?",$param);
    }
    
    /**
     * Проверяет существование ответа на вопрос, возвращает id ответа
     * @param array $param
     * @return array
     */
    public function checkAnswer($param)
    {
        $rez = $this->prepare("SELECT answer_id FROM questions WHERE id= ?",$param);
        return array_shift($rez);
    }

    /**
     * Изменение ответа
     * @param array $param
     */
    public function actionChangeAnswer($param)
    {
        $this->prepare("UPDATE answers SET answer = :answer, admin_id = :admin_id WHERE id = :id",$param);
    }
    
    /**
     * Добавление нового ответа, возвращает id созданного ответа
     * @param array $param
     * @return string
     */
    public function addAnswer($param)
    {
        return $this->prepare("INSERT INTO answers (answer, admin_id) VALUES (:answer, :admin_id)",$param);
    }
    
    /**
     * Обновляет id ответа в вопросе
     * @param array $param
     */
    public function updateAnswerId($param)
    {
        $this->prepare("UPDATE questions SET answer_id = ? WHERE id = ?",$param);
    }

    /**
     * Обновляет категорию вопроса
     * @param array $param
     */
    public function actionUpdateCategory($param)
    {
        $this->prepare("UPDATE questions SET category_id = ? WHERE id = ?",$param);
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
