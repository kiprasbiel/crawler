<?php

namespace Crawler;

class Crawler {
    private $html;
    private $url;

    public ?array $urls;

    public function getAllArticles(string $url): array {
        $allArticles = [];
        $mainArticle = $this->singleArticle($url);
        $allArticles[] = $mainArticle;

        foreach ($mainArticle['urls'] as $article){
            $allArticles[] = $this->singleArticle($article['href']);
        }

        return $allArticles;
    }

    private function curl(string $url): void {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $this->html = str_get_html(curl_exec($ch));
        curl_close($ch);
    }

    private function singleArticle(string $url): array {
        $this->url = $url;
        $this->curl($this->url);
        return [
            'url' => $url,
            'title' =>$this->getTitle(),
            'description' => $this->getDescription(),
            'keywords' => $this->getKeywords(),
            'urls' => $this->getUrls(),
            'author' => $this->getAuthor(),
            'content' => $this->getContent(),
        ];
    }

    public function getTitle(): string {
        return $this->html->find('title', 0)->plaintext;
    }

    public function getDescription(): string {
        return trim($this->html->find('.intro[itemprop="description"]', 0)->plaintext);
    }

    public function getKeywords(): array {
        $kwStr = $this->html->find('meta[name="keywords"]', 0)->content;
        return explode(', ', $kwStr);
    }

    public function getUrls(): array {
        $this->urls = [];
        $allLinks = $this->html->find('a.title');
        foreach($allLinks as $link) {
            if(array_search($link->href, array_column($this->urls, 'href')) === false && strpos($link->href, '15min')){
                $this->urls[] = [
                    'title' => trim($link->plaintext),
                    'href' => $link->href,
                ];
            }
        }
        return $this->urls;
    }

    public function getAuthor(): ?string {
        return $this->html->find('a.author-name', 0)->href;
    }

    public function getContent(): ?string {
        if(strpos($this->url, '/video/')){
            // Video
            return "";
        }
        else{
            // Normal article
            $allContent = $this->html->find('div.article-content > div.text', 0)->children;
        }
        $content = '';
        foreach($allContent as $para){
            if($para->tag === 'p' || preg_match('/h[1-6]/', $para->tag)) {
                $content .= $para->plaintext . ' <br> ';
            }
            elseif($para->child->tag === 'p' || preg_match('/h[1-6]/', $para->child->tag)) {
                $content .= $para->child->plaintext . ' <br> ';
            }
            // Table
            elseif($para->child->tag === 'table' || $para->tag === 'table') {
                // Header
                $content .= '<br>';
                if ($para->find('thead > tr', 0)->children){
                    foreach($para->find('thead > tr', 0)->children as $th) {
                        $content .= $th->plaintext . ' | ';
                    }
                    $content .= '<br>';
                    // Body
                    foreach($para->find('tbody > tr') as $tr){
                        foreach($tr->children as $td){
                            $content .= $td->plaintext . ' | ';
                        }
                        $content .= '<br>';
                    }
                    $content .= '<br>';
                }
            }
        }
        return $content;
    }
}
