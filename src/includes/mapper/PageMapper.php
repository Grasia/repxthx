<?php

class PageMapper extends AbstractMapper {

    /**
     * Inserts a new page
     * @param ReptxThxPage $page
     * @return boolean
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

    /**
     * gets a page given a pageId
     * @param type $id
     * @return type
     */
    public function getById($id) {

        $db = $this->dbFactory->getForRead();

        $row = $db->selectRow('reptxthx_page', '*', array('page_id' => $id), __METHOD__);

        if (!$row) {
            return null;
        }

        return ReptxThxPage::newFromRow($row);
    }

    /**
     * Gets the $limit first pages ordered by reptxThx_page_id
     * @param type $limit
     * @param type $last
     * @return array
     */
    public function getPagesArray($limit, $last) {
        $data = array();
        $db = $this->dbFactory->getForRead();

        $res = $db->select('reptxthx_page', '*', "reptxthx_page_id > $last", __METHOD__, array('LIMIT' => $limit, 'ORDER BY' => 'reptxThx_page_id'));

        for ($i = 0; $i < $res->numRows(); $i++) {
            array_push($data, $res->next());
        }

        return $data;
    }

    /**
     * Returns the number of pages
     * @return type
     */
    public function getWikPageNumber() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('page', array('numArticles' => 'COUNT(*)'), '', __METHOD__);

        return $res->numArticles;
    }

    /**
     * Returns the fitness average
     * @return type
     */
    public function getFitnessAvg() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_page', array('fitnessAvg' => 'avg(page_fitness_value)'), '', __METHOD__);

        return $res->fitnessAvg;
    }

    /**
     * Returns the fitness sum
     * @return type
     */
    public function getFitnessSum() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_page', array('fitnessSum' => 'sum(page_fitness_value)'), '', __METHOD__);

        return $res->fitnessSum;
    }

    /**
     * Returns the sum of the squered fitness value
     * @return type
     */
    public function getFitSqrSum() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_page', array('sqrSum' => 'SUM(page_temp_fitness_value * page_temp_fitness_value)'), '', __METHOD__);

        return $res->sqrSum;
    }

    /**
     * Returns an array with all the pages not inserted into
     * reptxths tables.
     * @return array
     */
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

    /**
     * Updates temporary fitness value 
     * @param type $pageId
     * @param type $value
     * @return type
     */
    public function updateTempFitValue($pageId, $value) {
        $db = $this->dbFactory->getForWrite();

        $res = $db->update('reptxthx_page', array('page_temp_fitness_value' => $value), array('page_id' => $pageId), __METHOD__);

        return $res;
    }

    /**
     * Updates fitness value
     * @param type $pageId
     * @param type $fitVal
     * @param type $timestamp
     * @return type
     */
    public function updateFitnessValue($pageId, $fitVal, $timestamp) {
        $db = $this->dbFactory->getForWrite();

        $res = $db->update('reptxthx_page', array('page_fitness_value' => $fitVal,'page_last_fitness_timestamp' => $timestamp), array('page_id' => $pageId), __METHOD__);

        return $res;
    }

}
