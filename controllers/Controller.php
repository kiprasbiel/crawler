<?php


namespace Controllers;


class Controller
{
    public function view($view, $data = []){
        // Check for view file
        if(file_exists('../views/' . $view . '.php')){
            require_once '../views/' . $view . '.php';
        } else {
            // View does not exist
            die('View does not exist');
        }
    }
}