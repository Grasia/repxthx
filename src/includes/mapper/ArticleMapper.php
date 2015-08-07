<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ArticleMapper extends AbstractMapper {

    /**
     * Insert an event record
     *
     * @param EchoEvent
     * @return int|bool
     */

    public function insert(ReptxThxArticle $article) {
        $dbw = $this->dbFactory->getForWrite();

        $id = $dbw->nextSequenceValue('reptxThx_article_id');

        if ($id) {
            $row['article_id'] = $id;
        }

        $row = $article->toDbArray();

        $res = $dbw->insert('reptxThx_article', $row, __METHOD__);
        if ($res) {
            $id = $dbw->insertId();
            return $id;
        } else {
            return false;
        }
    }

    /**
     * Create an EchoEvent by id
     *
     * @param int
     * @param boolean
     * @return EchoEvent
     * @throws MWException
     */
    public function getByArticleId($id) {

        $db = $this->dbFactory->getForRead();

        $row = $db->selectRow('reptxThx_article', '*', array('article_id' => $id), __METHOD__);

        if (!$row) {
            throw new MWException("No Users found with ID: $id");
        }

        return ReptxThxArticle::newFromRow($row);
    }

    public function getUsersArray($limit, $last) {
        $data = array();
        $db = $this->dbFactory->getForRead();

        $res = $db->select('reptxthx_article', '*', "reptxthx_article_id > $last", __METHOD__, array('LIMIT' => $limit, 'ORDER BY' => 'reptxthx_article_id'));

        for ($i = 0; $i < $res->numRows(); $i++) {
            array_push($data, $res->next());
        }
        
        return $data;
    }

    public function getWikiArticleNumber() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('page', array('numArticles' => 'COUNT(*)'), '', __METHOD__);

        return $res->numArticles;
    }
    
    public function getFitnessAvg(){
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_article', array('fitnessAvg' => 'avg(article_fitness_value)'), '', __METHOD__);

        return $res->fitnessAvg;
    }

    public function getNewArticles() {
        $data = array();
        $db = $this->dbFactory->getForRead();
        $limit = 250;

        $res = $db->select(array('mediawikiArticles' => 'page', 'extensionArticles' => 'reptxthx_article'), 'mediawikiArticles.page_id', 'extensionArticles.article_id IS NULL', __METHOD__, array('LIMIT' => $limit), array('extensionArticles' => array('LEFT JOIN', 'mediawikiArticles.page_id = extensionUsers.article_id')));

        for ($i = 0; $i < $res->numRows(); $i++) {
            array_push($data, $res->fetchRow());
        }

        return $data;
    }

}
