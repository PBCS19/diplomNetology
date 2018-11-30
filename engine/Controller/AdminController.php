<?php

namespace Engine\Controller;

use Engine\Model\AdminModel;
use Engine\Core\Response\Response;

class AdminController 
{
    public function auth() 
    {
        $log = isset($_POST['login']) ? $_POST['login'] : '';
        $pas = isset($_POST['password']) ? $_POST['password'] : '';
        $errors = $this->loginPass($log,$pas);
        $this->checkErrorsAuth($errors);
    }

    public function index()
    {
        if (!isset($_SESSION['admin_id'])) {
            Response::redirect('/admin/auth');
        } else {
            $action = new AdminModel();
            $listAdmins = $this->listAdmin();
            if(isset($_POST['deladmin'])) {
                $action->actionDelAdmin(['id'=>$_POST['deladmin']]);
            }
            $categories = $action->getCategories();
            $questions = $action->getQuestionCategories('all');
            $questionsNoAnswer = $action->getQuestionCategories('onlyNoAnswer');
            require_once __DIR__ . '/../View/admin.php';
        }    
    }
    
    public function edit()
    {   
        if (isset($_POST['goEdit'])) {
            $action = new AdminModel();
            $questions = $action->getQuestionCategories([$_POST['goEdit']]);
            $categories = $action->getCategories();
            require_once __DIR__ . '/../View/edit.php';
        } elseif(isset($_POST['updateQuestion'])) {
            $this->updateQuestion();
            Response::redirect('/admin');
        } else {
            Response::redirect('/admin');
        }
    }

    private function loginPass($login, $password) 
    {
        $errors = [];
        if (!empty($login) && !empty($password)) {
            $auth = new AdminModel();
            $login = $auth->checkLoginPass([$login, $password]);
            if (!$login['id']) {
                $errors[] = 'Не верный логин или пароль!';
            }
        } else {
            $errors[] = 'Введите логин или пароль!';
        }
        if (!empty($errors)) {
            return $errors;
        } else {
            $_SESSION['admin_id'] = $login['id'];
        }
    }
    
    private function checkErrorsAuth($errors) 
    {
        if (!empty($errors)) {
            $auth = new AdminModel();
            if (!isset($_SESSION['countCaptcha']) || $_SESSION['countCaptcha'] < 6) {
                $count = isset($_SESSION['countCaptcha']) ? $_SESSION['countCaptcha'] : 0;
                $auth->countCaptcha($count);
                require_once __DIR__ . '/../View/login.php';
            } elseif($_SESSION['countCaptcha'] >= 6 && $_SESSION['countCaptcha'] < 12) {
                $auth->countCaptcha($_SESSION['countCaptcha']);
                if (isset($_POST['g-recaptcha-response'])) {
                    $auth->checkGoogleCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                }
                require_once __DIR__ . '/../View/loginCaptcha.php';
            } else {
                setcookie('ban','бан на 1 час', time()+3600);
                http_response_code(403);
                exit('Подождите часок, мы вас заблокировали, а затем попробуйте снова');
            }    
        } else {
            Response::redirect('/admin');
        }
    }
    
    private function listAdmin()
    {
        $get = new AdminModel();
        return $get->getListAdmin();
    }
    
    public function addAdmin()
    {
        if (isset($_POST['submit'])) {
            $action = new AdminModel();
            
            $errors = $action->checkErrorsAddAdmin($_POST);
            
            if (empty($errors)) {
                $action->actionAddAdmin(['login'=>$_POST['login'],'password'=>$_POST['password']]);
                Response::redirect('/admin');
            }
        }
        require_once __DIR__ . '/../View/addAdmin.php';
    }
    
    public function changePassword()
    {
        $action = new AdminModel();
        $action->actionChangePassword(['id'=>$_POST['goEditPas'], 'pas'=>$_POST['editPas']]);
        Response::redirect('/admin');
    }
    
    public function delAdmin()
    {
        $action = new AdminModel();
        $action->actionDelAdmin(['id'=>$_POST['deladmin']]);
        Response::redirect('/admin');
    }
    
    public function addAnswer()
    {
        $action = new AdminModel();
        $action->actionAddAnswer();
    }
    
    public function statusQuestion()
    {
        $action = new AdminModel();
        if(isset($_POST['questionHide'])) {
            $action->actionStatusQuestion([1,$_POST['questionHide']]);
        }
        if(isset($_POST['questionOpen'])) {
            $action->actionStatusQuestion([0,$_POST['questionOpen']]);
        }
        Response::redirect('/admin');
    }
    
    public function delCategory()
    {
        $action = new AdminModel();
        $action->actionDelCategory([$_POST['delCategory']]);
        Response::redirect('/admin');
    }
    
    public function addCategory()
    {
        $action = new AdminModel();
        if (!empty($_POST['addCategory'])) {
            $action->actionAddCategory([$_POST['addCategory']]);
        }
        Response::redirect('/admin');
    }
    
    public function delQuestion()
    {
        $action = new AdminModel();
        $action->actionDelQuestion([$_POST['delQuestion']]);
        Response::redirect('/admin');
    }
    
    private function updateQuestion()
    {
        $action = new AdminModel();
        if (!empty($_POST['changeName'])) {
            $userId = $action->checkUser([$_POST['updateQuestion']]);
            $action->actionChangeName([$_POST['changeName'],$userId['user_id']]);
        }
        if (!empty($_POST['changeQuestion'])) {
            $action->actionChangeQuestion([$_POST['changeQuestion'],$_POST['updateQuestion']]);
        }
        if (!empty($_POST['changeAnswer'])) {
            $answerId = $action->checkAnswer([$_POST['updateQuestion']]);
            if ($answerId['answer_id'] == null)
            {
                $answerId = $action->addAnswer(['answer'=>$_POST['changeAnswer'],'admin_id'=>$_SESSION['admin_id']]);
                $action->updateAnswerId([$answerId,$_POST['updateQuestion']]);
            } else {
                $action->actionChangeAnswer(['answer'=>$_POST['changeAnswer'],'admin_id'=>$_SESSION['admin_id'],'id'=>$answerId['answer_id']]);
            }
            $action->actionStatusQuestion([$_POST['status'],$_POST['updateQuestion']]);
        }
        if (!empty($_POST['updateCategory'])) {
            $action->actionUpdateCategory([$_POST['updateCategory'],$_POST['updateQuestion']]);
        }
    }
}
