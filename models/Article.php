<?php

namespace Models;

use Crawler\Crawler;
use Crawler\Database;
use PDOException;

class Article
{
    private Database $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function addArticles(string $url) {
        $crawler = new Crawler();
        $articleArray = $crawler->getAllArticles($url);

        foreach($articleArray as $article) {
            $this->addArticle($article);
        }
    }

    public function addArticle(array $data) {
        $this->db->query('
            INSERT INTO articles (url, name, content, links, keywords, author, description) 
            VALUES(:url, :name, :content, :links, :keywords, :author, :description)
        ');
        $this->db->bind(':url', $data['url']);
        $this->db->bind(':name', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':links', $this->parseLinks($data['urls']));
        $this->db->bind(':keywords', $this->parseKeywords($data['keywords']));
        $this->db->bind(':author', $data['author']);

        try {
            $this->db->execute();
        }
        catch (PDOException $e){
            if($e->getCode() === "23000"){
                echo "<br>Straipsnis pavadinimu <br>" . $data['title'] . '<br>duomenų bazėje jau egzistuoja. Praleidžiama.<br>';
            }
            else {
                echo $e->getMessage();
            }
        }
    }

    private function parseKeywords(array $keywords): string {
        return implode('|', $keywords);
    }

    private function parseLinks(array $links): string {
        return json_encode($links, JSON_UNESCAPED_UNICODE);
    }
}