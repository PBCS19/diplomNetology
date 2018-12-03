<?php

namespace Engine\Controller;

use Engine\Core\ParentController\Controller;
use Engine\Model\QuestionsModel;
use Engine\Model\UserModel;

class MainController extends Controller
{
    /**
     * Главная
     */
    public function index()
    {
        $sth = new QuestionsModel();
        $categories = $sth->getCategories();
        $questions = $sth->getQuestionCategories('status');
        
        $array = [
            'categories' => $categories,
            'questions'  => $questions,
        ];
        $this->requireOnce('index.php', $array);
    }
    
    /**
     * Добавление нового вопроса
     */
    public function add()
    {
        $sth = new QuestionsModel();
        $categories = $sth->getCategories();
        $errors = [];
        if (isset($_POST['submit'])) {
            
            $errors = $sth->checkErrorsQuestions($_POST);
            
            if (empty($errors)) {
                $user = new UserModel();
                $param = [
                    'name'  => $_POST['name'],
                    'email' => $_POST['email']
                ];
                $checkUser = $user->getIdUser($param);
                if (!empty($checkUser['id'])) {
                    $param = [
                        'question'    => $_POST['question'],
                        'category_id' => $_POST['category'],
                        'user_id'     => $checkUser['id']
                    ];
                } else {
                    $idUser = $user->addUser($param);
                    $param = [
                        'question'    => $_POST['question'],
                        'category_id' => $_POST['category'],
                        'user_id'     => $idUser
                    ];
                }
                $sth->addQuestion($param);
                $errors[] = 'Ваш вопрос записан!';
            }
        }
        
        $array = [
            'categories' => $categories,
            'errors'     => $errors,
        ];
        $this->requireOnce('addQuestion.php', $array);
    }
}
