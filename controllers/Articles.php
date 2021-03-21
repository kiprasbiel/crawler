<?php

namespace Controllers;

use Models\Article;

class Articles extends Controller
{
    public function index() {
        $this->view('articleSearch');
    }

    public function submit() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'url' => $_POST['url'],
            ];

            if(empty($data['url'])) {
                die('URL negali būti tuščias');
            }

            $article = new Article();
            $article->addArticles($data['url']);
            
        } else {
            $this->view('articleSearch');
        }
    }
}