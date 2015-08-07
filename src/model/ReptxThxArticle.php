<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ReptxThxArticle extends AbstractModelElement {

    protected $id;
    protected $articleId;
    protected $articleFitnessValue;
    protected $lastFitnessUpdateTimestamp;

    protected function __construct() {
        
    }

    function __toString() {
        return "ReptxThxArticle(id={$this->id}; "
                . "articleId={$this->articleId}; "
                . "articleFitnessValue={$this->articleFitnessValue}; "
                . "lastFitnessUpdateTimestamp={$this->lastFitnessUpdateTimestamp}; ";
    }

    public static function create($articleId, $articleFitnessValue, $lastFitnessUpdateTimestamp = "") {
        if (wfReadOnly()) {
            throw new ReadOnlyError();
        }

        $obj = new ReptxThxUser;

        if (empty($articleId)) {
            throw new MWException("'type' parameter missing");
        }

        if (!isset($articleFitnessValue)) {
            throw new MWException("'sender' parameter missing");
        }

        if (empty($lastFitnessUpdateTimestamp)) {
            $obj->lastFitnessUpdateTimestamp = wfTimestampNow();
        } else {
            $obj->lastFitnessUpdateTimestamp = $lastRepUpdateTimestamp;
        }

        $obj->id = false;
        $obj->articleId = $articleId;
        $obj->articleFitnessValue = $articleFitnessValue;

        $obj->insert();

        return $obj;
    }

    protected function insert() {
        $articleMapper = new ArticleMapper();
        return $articleMapper->insert($this);
    }

    public function loadFromRow($row) {
        $this->id = $row->reptxthx_article_id;
        $this->articleId = $row->article_id;

        $this->articleFitnessValue = $row->article_fitness_value;
        $this->lastFitnessUpdateTimestamp = $row->article_last_fitness_timestamp;
    }

    public function loadFromID($id) {
        $articleMapper = new ArticleMapper();
        $article = $articleMapper->getById($id);

        // Copy over the attribute
        $this->id = $article->reptxthx_article_id;
        $this->articleId = $article->article_id;

        $this->articleFitnessValue = $article->article_fitness_value;
        $this->lastFitnessUpdateTimestamp = $article->article_last_fitness_timestamp;
    }

    public static function newFromRow($row) {
        $obj = new ReptxThxArticle();
        $obj->loadFromRow($row);
        return $obj;
    }

    public function toDbArray() {
        $data = array(
            'article_id' => $this->articleId,
            'article_fitness_value' => $this->userReputationValue,
            'article_last_fitness_timestamp' => $this->userCreditValue,
        );
        if ($this->id) {
            $data['reptxthx_article_id'] = $this->id;
        }

        return $data;
    }

    public static function getArticlesChunk(&$last) {

        $data = array();

        $articleMapper = new ArticleMapper();
        $articlesChunk = $articleMapper->getArticlesArray(250, $last);

        foreach ($articlesChunk as $articleRow) {
            array_push($data, self::newFromRow($articleRow));
        }
        if (!empty($data)) {
            $last = end($data)->getId();
        }
        return $data;
    }

    public static function insertNewArticles() {
        $articleMapper = new ArticleMapper();
        $newArticles = $articleMapper->getNewArticles();

        $defFitnessVal = self::getDefaultFitnessValue();

        while (!empty($newArticles)) {
            foreach ($newArticles as $user) {
                self::create($user['user_id'], $defFitnessVal);
            }

            $newArticles = $articleMapper->getNewArticles();
        }
    }

    public static function getDefaultFitnessValue() {
        $articleMapper = new ArticleMapper();
        $numArticles = $articleMapper->getWikiArticleNumber();

        return 1 / sqrt($numArticles);
    }
    
    public static function getFitnessAvg(){
        $articleMapper = new ArticleMapper();
        $avgArticles = $articleMapper->getFitnessAvg();
        
        return $avgArticles;
    }

    public function getArticleId() {
        return $this->articleId;
    }

    public function getId() {
        return $this->id;
    }

    public function getFitness() {
        return $this->articleFitnessValue;
    }

}
