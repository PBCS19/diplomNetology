<?php

namespace Engine\Model;

use Engine\Core\Database\Connection;
use Engine\Core\ParentModel\Model;

class AdminModel extends Model
{
    /**
     * Проверяет логин и пароль на существование
     * @param array $param
     * @return array
     */
    public function checkLoginPass($param) 
    {
        $sth = Connection::get()->connect()->prepare(
                "SELECT id FROM admins WHERE login= ? AND password= ?");
        $sth->execute($param);
        return $sth->fetch();
    }
    
    /**
     * Считает кол-во не верно введенных логинов и паролей
     * @param int $countCaptcha
     * @return int
     */
    public function countCaptcha($countCaptcha) 
    {
        $countCaptcha++;
        $_SESSION['countCaptcha'] = $countCaptcha;
        return $countCaptcha;
    }
    
    /**
     * Возвращает id,логин,пароль администратора.
     * @return array
     */
    public function getListAdmin() 
    {
        $sth = Connection::get()->connect()->prepare(
                "SELECT id, login, password FROM admins");
        $sth->execute();
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Добавляет нового администратора
     * @param array $param
     */
    public function actionAddAdmin($param)
    {
        $sth = Connection::get()->connect()->prepare(
                "INSERT INTO admins (login, password) VALUES (:login, :password)");
        $sth->execute($param);
    }
    
    /**
     * Изменяет пароль администратора
     * @param array $param
     */
    public function actionChangePassword($param)
    {
        $sth = Connection::get()->connect()->prepare(
                "UPDATE admins SET password=:pas WHERE id=:id");
        $sth->execute($param);
    }
    
    /**
     * Удаляет администратора
     * @param array $param
     */
    public function actionDelAdmin($param)
    {
        $sth = Connection::get()->connect()->prepare(
                "DELETE FROM admins WHERE id=:id LIMIT 1");
        $sth->execute($param);
    }
    
    /**
     * Проверяет ошибки в веденный данных регисрации администратора
     * @param array $param
     * @return string
     */
    public function checkErrorsAddAdmin($param)
    {
        $errors = [];
        if (empty($param['login'])) {
            $errors[] = 'Введите логин';
        }
        
        elseif (empty($param['password'])) {
            $errors[] = 'Введите пароль';
        }
        
        elseif (!empty($id = $this->checkAdmin([$param['login']]))) {
            $errors[] = 'Такой администратор существует!';
        }
        
        return $errors;
    }
    
    /**
     * Проверяет логин администратора на существование
     * @param array $param
     * @return array
     */
    private function checkAdmin($param) {
        $sth = Connection::get()->connect()->prepare(
                "SELECT id FROM admins WHERE login= ?");
        $sth->execute($param);
        return $sth->fetch();
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
}
