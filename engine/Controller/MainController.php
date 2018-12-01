<?php

namespace Engine\Controller;

use Engine\Core\ParentController\Controller;
use Engine\Model\QuestionsModel;
use Engine\Model\UserModel;

class MainController extends Controller 
{
    
    public function index()
    {
        $sth = new QuestionsModel();
        $categories = $sth->getCategories();
        $questions = $sth->getQuestionCategories('status');
        require_once __DIR__ . '/../View/index.php';
    }
    
    /**
     * Добавление нового вопроса
     */
    public function add()
    {
        $sth = new QuestionsModel();
        $categories = $sth->getCategories();
        if (isset($_POST['submit'])) {
            
            $errors = $sth->checkErrorsQuestions($_POST);
            
            if (empty($errors)) {
                $user = new UserModel();
                $param = ['name'=>$_POST['name'], 'email'=>$_POST['email']];
                $checkUser = $user->getIdUser($param);
                if (!empty($checkUser['id'])) {
                    $param = ['question'=>$_POST['question'],'category_id'=>$_POST['category'],'user_id'=>$checkUser['id']];
                } else {
                    $idUser = $user->addUser($param);
                    var_dump($idUser);
                    $param = ['question'=>$_POST['question'],'category_id'=>$_POST['category'],'user_id'=>$idUser];
                }
                $sth->addQuestion($param);
                $errors[] = 'Ваш вопрос записан!';
            }
        }
        require_once __DIR__ . '/../View/addQuestion.php';
    }
}
