<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PageMapper extends AbstractMapper {

    /**
     * Insert an event record
     *
     * @param EchoEvent
     * @return int|bool
     */
    public function insert(ReptxThxPage $page) {
        $dbw = $this->dbFactory->getForWrite();

        $id = $dbw->nextSequenceValue('reptxThx_page_id');

        if ($id) {
            $row['page_id'] = $id;
        }

        $row = $page->toDbArray();

        $res = $dbw->insert('reptxThx_page', $row, __METHOD__);
        if ($res) {
            $id = $dbw->insertId();
            return $id;
        } else {
            return false;
        }
    }

    public function getById($id) {

        $db = $this->dbFactory->getForRead();

        $row = $db->selectRow('reptxthx_page', '*', array('page_id' => $id), __METHOD__);

        if (!$row) {
            error_log("reptxthx_page null");
            return null;
        }

        return ReptxThxPage::newFromRow($row);
    }

    public function getPagesArray($limit, $last) {
        $data = array();
        $db = $this->dbFactory->getForRead();

        $res = $db->select('reptxthx_page', '*', "reptxthx_page_id > $last", __METHOD__, array('LIMIT' => $limit, 'ORDER BY' => 'reptxThx_page_id'));

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

    public function getFitnessAvg() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_page', array('fitnessAvg' => 'avg(page_fitness_value)'), '', __METHOD__);

        return $res->fitnessAvg;
    }

    public function getFitSqrSum() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_page', array('sqrSum' => 'SUM(page_temp_fitness_value * page_temp_fitness_value)'), '', __METHOD__);

        return $res->sqrSum;
    }

    public function getNewPages() {
        $data = array();
        $db = $this->dbFactory->getForRead();
        $limit = 250;

        $res = $db->select(array('mediawikiPages' => 'page', 'extensionPages' => 'reptxthx_page'), 'mediawikiPages.page_id', 'extensionPages.page_id IS NULL', __METHOD__, array('LIMIT' => $limit), array('extensionPages' => array('LEFT JOIN', 'mediawikiPages.page_id = extensionPages.page_id')));

        for ($i = 0; $i < $res->numRows(); $i++) {
            array_push($data, $res->fetchRow());
        }

        return $data;
    }

    public function updateTempFitValue($pageId, $value) {
        $db = $this->dbFactory->getForWrite();

        $res = $db->update('reptxthx_page', array('page_temp_fitness_value' => $value), array('page_id' => $pageId), __METHOD__);

        return $res;
    }

}
