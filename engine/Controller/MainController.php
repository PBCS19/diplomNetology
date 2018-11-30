<?php

namespace Engine\Controller;

use Engine\Core\ParentController\Controller;
use Engine\Model\MainModel;

class MainController extends Controller 
{
    
    public function index()
    {
        $sth = new MainModel();
        $categories = $sth->getCategories();
        $questions = $sth->getQuestionCategories('status');
        require_once __DIR__ . '/../View/index.php';
    }
    
    public function add()
    {
        $sth = new MainModel();
        $categories = $sth->getCategories();
        if (isset($_POST['submit'])) {
            
            $errors = $sth->checkErrorsQuestions($_POST);
            
            if (empty($errors)) {
                $param = ['name'=>$_POST['name'], 'email'=>$_POST['email']];
                $checkUser = $sth->getIdUser($param);
                if (!empty($checkUser['id'])) {
                    $param = ['question'=>$_POST['question'],'category_id'=>$_POST['category'],'user_id'=>$checkUser['id']];
                } else {
                    $sth->addUser($param);
                    $idUser = $sth->getIdUser($param);
                    $param = ['question'=>$_POST['question'],'category_id'=>$_POST['category'],'user_id'=>$idUser['id']];
                }
                $sth->addQuestion($param);
                $errors[] = 'Ваш вопрос записан!';
            }
        }
        require_once __DIR__ . '/../View/addQuestion.php';
    }
}
