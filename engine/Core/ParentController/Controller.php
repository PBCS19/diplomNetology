<?php

namespace Engine\Core\ParentController;

class Controller 
{
    public function requireOnce($view, $array)
    {
        require_once DIR_VIEW . $view;
    }
}
