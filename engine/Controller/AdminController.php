<?php

namespace Engine\Controller;

use Engine\Model\AdminModel;
use Engine\Model\QuestionsModel;
use Engine\Model\UserModel;
use Engine\Core\Response\Response;
use Engine\Core\ParentController\Controller;

class AdminController extends Controller
{
    /**
     * Авторизация
     */
    public function auth() 
    {
        $log = isset($_POST['login']) ? $_POST['login'] : '';
        $pas = isset($_POST['password']) ? $_POST['password'] : '';
        $errors = $this->loginPass($log,$pas);
        $this->checkErrorsAuth($errors);
    }

    /**
     * Главная
     */
    public function index()
    {
        if ( !isset($_SESSION['admin_id']) ) {
            Response::redirect('/admin/auth');
        } else {
            $action = new AdminModel();
            $listAdmins = $this->listAdmin();
            if( isset($_POST['deladmin']) ) {
                $action->actionDelAdmin(['id' => $_POST['deladmin']]);
            }
            $questionModel = new QuestionsModel();
            $categories = $questionModel->getCategories();
            $questions = $questionModel->getQuestionCategories('all');
            $questionsNoAnswer = $questionModel->getQuestionCategories('onlyNoAnswer');
            
            $array = [
                'listAdmins'        => $listAdmins,
                'categories'        => $categories,
                'questions'         => $questions,
                'questionsNoAnswer' => $questionsNoAnswer,
            ];
            $this->requireOnce('admin.php', $array);
        } 
    }
    
    /**
     * Редактирование вопроса
     */
    public function edit()
    {   
        if ( isset($_POST['goEdit']) ) {
            $action = new QuestionsModel();
            $questions = $action->getQuestionCategories([$_POST['goEdit']]);
            $categories = $action->getCategories();
            
            $array = [
                'categories' => $categories,
                'questions'  => $questions,
            ];
            $this->requireOnce('edit.php', $array);
            
        } elseif( isset($_POST['updateQuestion']) ) {
            $this->updateQuestion();
            Response::redirect('/admin');
        } else {
            Response::redirect('/admin');
        }
        
    }
    
    /**
     * Выход
     */
    public function logout()
    {
        session_destroy();
        Response::redirect('/');
        exit();
    }

    /**
     * Проверка логина и пароля, запись id администратора в сессию
     * @param string $login
     * @param string $password
     * @return array
     */
    private function loginPass($login, $password) 
    {
        $errors = [];
        if ( !empty($login) && !empty($password) ) {
            $auth = new AdminModel();
            $login = $auth->checkLoginPass([$login, $password]);
            if ( !$login['id'] ) {
                $errors[] = 'Не верный логин или пароль!';
            }
        } else {
            $errors[] = 'Введите логин или пароль!';
        }
        
        if ( empty($errors) ) {
            $_SESSION['admin_id'] = $login['id'];
        } 
        return $errors;
    }
    
    /**
     * Проверка количества не верно введенных логинов и паролей, либо авторизация
     * @param array $errors
     */
    private function checkErrorsAuth($errors) 
    {
        if ( !empty($errors) ) {
            $auth = new AdminModel();
            if ( !isset($_SESSION['countCaptcha']) || $_SESSION['countCaptcha'] < 6 ) {    //Без капчи
                
                $count = isset($_SESSION['countCaptcha']) ? $_SESSION['countCaptcha'] : 0;
                $auth->countCaptcha($count);
                $this->requireOnce('login.php', ['errors' => $errors]);
                
            } elseif( $_SESSION['countCaptcha'] >= 6 && $_SESSION['countCaptcha'] < 12 ) {  //С каптчей
                
                $auth->countCaptcha($_SESSION['countCaptcha']);
                if ( isset($_POST['g-recaptcha-response']) ) {
                    $auth->checkGoogleCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                }
                $this->requireOnce('loginCaptcha.php', ['errors' => $errors]);
                
            } else {                                                                     //Бан
                
                setcookie('ban','бан на 1 час', time()+3600);
                http_response_code(403);
                exit('Подождите часок, мы вас заблокировали, а затем попробуйте снова');
                
            }
        } else {
            Response::redirect('/admin');
        }
    }
    
    /**
     * Возвращает id,логин,пароль администратора.
     * @return array
     */
    private function listAdmin()
    {
        $get = new AdminModel();
        return $get->getListAdmin();
    }
    
    /**
     * Проверяет данные на ошибки и добавляет администратора
     */
    public function addAdmin()
    {
        $errors = [];
        if ( isset($_POST['submit']) ) {
            $action = new AdminModel();
            
            $errors = $action->checkErrorsAddAdmin($_POST);
            
            if ( empty($errors) ) {
                $action->actionAddAdmin(
                            [
                                'login' => $_POST['login'],
                                'password' => $_POST['password']
                            ]);
                Response::redirect('/admin');
            }
        }
        $this->requireOnce('addAdmin.php', ['errors' => $errors]);
    }
    
    /**
     * Изменяет пароль администратора
     */
    public function changePassword()
    {
        $action = new AdminModel();
        $action->actionChangePassword(
                    [
                        'id'  => $_POST['goEditPas'], 
                        'pas' => $_POST['editPas']
                    ]);
        Response::redirect('/admin');
    }
    
    /**
     * Удаляет администратора
     */
    public function delAdmin()
    {
        $action = new AdminModel();
        $action->actionDelAdmin(['id' => $_POST['deladmin']]);
        Response::redirect('/admin');
    }
    
    /**
     * Добавляет ответ
     */
    public function addAnswer()
    {
        $action = new AdminModel();
        $action->actionAddAnswer();
    }
    
    /**
     * Меняет статус вопроса (открыт/скрыт)
     */
    public function statusQuestion()
    {
        $action = new QuestionsModel();
        if( isset($_POST['questionHide']) ) {
            $action->actionStatusQuestion([1, $_POST['questionHide']]);
        }
        if( isset($_POST['questionOpen']) ) {
            $action->actionStatusQuestion([0, $_POST['questionOpen']]);
        }
        Response::redirect('/admin');
    }
    
    /**
     * Удаляет категорию и все вопросы в ней
     */
    public function delCategory()
    {
        $action = new QuestionsModel();
        $action->actionDelCategory([$_POST['delCategory']]);
        Response::redirect('/admin');
    }
    
    /**
     * Добавляет категорию
     */
    public function addCategory()
    {
        $action = new QuestionsModel();
        if ( !empty($_POST['addCategory']) ) {
            $action->actionAddCategory([$_POST['addCategory']]);
        }
        Response::redirect('/admin');
    }
    
    /**
     * Удаляет вопрос
     */
    public function delQuestion()
    {
        $action = new QuestionsModel();
        $action->actionDelQuestion([$_POST['delQuestion']]);
        Response::redirect('/admin');
    }
    
    /**
     * Обновляет вопрос.
     */
    private function updateQuestion()
    {
        $action = new QuestionsModel();
        if ( !empty($_POST['changeName']) ) {
            $user = new UserModel();
            $userId = $user->checkUser([$_POST['updateQuestion']]); //Получение id юзера по id вопроса
            $user->actionChangeName([$_POST['changeName'], $userId['user_id']]); //Изменение имени
        }
        if ( !empty($_POST['changeQuestion']) ) {
            $action->actionChangeQuestion([$_POST['changeQuestion'], $_POST['updateQuestion']]); //Изменение вопроса по id вопроса
        }
        if ( !empty($_POST['changeAnswer']) ) { //Изменение ответа по id вопроса
            $action->changeAnswer();
        }
        if ( !empty($_POST['updateCategory']) ) {
            $action->actionUpdateCategory([$_POST['updateCategory'], $_POST['updateQuestion']]); //Изменение категории по id вопроса
        }
    }
}
