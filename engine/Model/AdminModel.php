<?php

namespace Engine\Model;

use Engine\Core\Database\Connection;

class AdminModel
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
    private function checkAdmin($param) 
    {
        $sth = Connection::get()->connect()->prepare(
                "SELECT id FROM admins WHERE login= ?");
        $sth->execute($param);
        return $sth->fetch();
    }
    
    public function checkGoogleCaptcha($gRecaptchaResponse, $ip) 
    {
        $urlToGoogleApi = "https://www.google.com/recaptcha/api/siteverify";
        $secretKey = '6LfASncUAAAAAG9zh38syXzQcqO353hMGUq2ZboX';
        $query = $urlToGoogleApi . '?secret=' . $secretKey . '&response=' . $gRecaptchaResponse . '&remoteip=' . $ip;
        $data = json_decode(file_get_contents($query));
        if ($data->success) {
            // Продолжаем работать с данными для авторизации из POST массива
        } else {
            exit('Извините, но похоже вы робот \(0_0)/');
        }
    }
}
