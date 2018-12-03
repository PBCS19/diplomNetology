<?php

namespace Engine\Model;

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
        return $this->prepareFetch("SELECT id FROM admins WHERE login= ? AND password= ?", $param);
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
        return $this->prepareFetchAll("SELECT id, login, password FROM admins", []);
    }

    /**
     * Добавляет нового администратора
     * @param array $param
     */
    public function actionAddAdmin($param)
    {
        $this->lastInsertId(
                "INSERT INTO admins (login, password) VALUES (:login, :password)",
                $param);
    }
    
    /**
     * Изменяет пароль администратора
     * @param array $param
     */
    public function actionChangePassword($param)
    {
        $this->prepare("UPDATE admins SET password=:pas WHERE id=:id", $param);
    }
    
    /**
     * Удаляет администратора
     * @param array $param
     */
    public function actionDelAdmin($param)
    {
        $this->prepare("DELETE FROM admins WHERE id=:id LIMIT 1", $param);
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
        return $this->prepareFetch("SELECT id FROM admins WHERE login= ?", $param);
    }
    
    public function checkGoogleCaptcha($gRecaptchaResponse, $ip) 
    {
        $urlToGoogleApi = "https://www.google.com/recaptcha/api/siteverify";
        $secretKey = '6LfASncUAAAAAG9zh38syXzQcqO353hMGUq2ZboX';
        $query = $urlToGoogleApi
                . '?secret=' . $secretKey
                . '&response=' . $gRecaptchaResponse
                . '&remoteip=' . $ip;
        $data = json_decode(file_get_contents($query));
        if ($data->success) {
            // Продолжаем работать с данными для авторизации из POST массива
        } else {
            exit('Извините, но похоже вы робот \(0_0)/');
        }
    }
}
