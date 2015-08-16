<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ReptxThxPage extends AbstractModelElement {

    protected $id;
    protected $pageId;
    protected $pageFitnessValue;
    protected $pageTempFitnessValue;
    protected $lastFitnessUpdateTimestamp;

    protected function __construct() {
        
    }

    function __toString() {
        return "ReptxThxPage(id={$this->id}; "
                . "pageId={$this->pageId}; "
                . "pageFitnessValue={$this->pageFitnessValue}; "
                . "pageFitnessValue={$this->pageTempFitnessValue}; "
                . "lastFitnessUpdateTimestamp={$this->lastFitnessUpdateTimestamp}; ";
    }

    public static function create($pageId, $pageFitnessValue, $lastFitnessUpdateTimestamp = "") {
        if (wfReadOnly()) {
            throw new ReadOnlyError();
        }

        $obj = new ReptxThxPage;

        if (empty($pageId)) {
            throw new MWException("'type' parameter missing");
        }

        if (!isset($pageFitnessValue)) {
            throw new MWException("'sender' parameter missing");
        }

        if (empty($lastFitnessUpdateTimestamp)) {
            $obj->lastFitnessUpdateTimestamp = wfTimestampNow();
        } else {
            $obj->lastFitnessUpdateTimestamp = $lastRepUpdateTimestamp;
        }

        $obj->id = false;
        $obj->pageId = $pageId;
        $obj->pageFitnessValue = $pageFitnessValue;
        $obj->pageTempFitnessValue = $pageFitnessValue;

        $obj->insert();

        return $obj;
    }

    public static function newFromId($page_id) {
        error_log("page id = $page_id");
        $obj = new ReptxThxPage();
        $obj->loadFromID($page_id);
        return $obj;
    }

    protected function insert() {
        $articleMapper = new PageMapper();
        return $articleMapper->insert($this);
    }

    public function loadFromRow($row) {
        $this->id = $row->reptxthx_page_id;
        $this->pageId = $row->page_id;

        $this->pageFitnessValue = $row->page_fitness_value;
        $this->pageTempFitnessValue = $row->page_temp_fitness_value;
        $this->lastFitnessUpdateTimestamp = $row->page_last_fitness_timestamp;
    }

    public function loadFromID($pageId) {
        $articleMapper = new PageMapper();
        $page = $articleMapper->getById($pageId);

        if (isset($page)) {
            $this->id = $page->id;
            $this->pageId = $page->pageId;

            $this->pageFitnessValue = $page->pageFitnessValue;
            $this->pageTempFitnessValue = $page->pageTempFitnessValue;
            $this->lastFitnessUpdateTimestamp = $page->lastFitnessUpdateTimestamp;
        }
    }

    public static function newFromRow($row) {
        $obj = new ReptxThxPage();
        $obj->loadFromRow($row);
        return $obj;
    }

    public function toDbArray() {
        $data = array(
            'page_id' => $this->pageId,
            'page_fitness_value' => $this->pageFitnessValue,
            'page_last_fitness_timestamp' => $this->lastFitnessUpdateTimestamp,
            'page_temp_fitness_value' => $this->pageTempFitnessValue
        );
        if ($this->id) {
            $data['reptxthx_article_id'] = $this->id;
        }

        return $data;
    }

    public static function getPagesChunk(&$last) {

        $data = array();

        $pageMapper = new PageMapper();
        $pagessChunk = $pageMapper->getPagesArray(250, $last);

        foreach ($pagessChunk as $pageRow) {
            array_push($data, self::newFromRow($pageRow));
        }
        if (!empty($data)) {
            $last = end($data)->getId();
        }
        return $data;
    }

    public static function insertNewPages() {
        error_log("insertNewArticles");
        $articleMapper = new PageMapper();
        $newArticles = $articleMapper->getNewPages();

        $defFitnessVal = self::getDefaultFitnessValue();

        while (!empty($newArticles)) {
            foreach ($newArticles as $article) {
                self::create($article['page_id'], $defFitnessVal);
            }

            $newArticles = $articleMapper->getNewPages();
        }
    }

    public static function getDefaultFitnessValue() {
        $articleMapper = new PageMapper();
        $numArticles = $articleMapper->getWikiArticleNumber();

        return 1 / sqrt($numArticles);
    }

    public static function getFitnessAvg() {
        $articleMapper = new PageMapper();
        $avgArticles = $articleMapper->getFitnessAvg();

        return $avgArticles;
    }

    public static function getFitNormValue() {
        $articleMapper = new PageMapper();
        $sqrSum = $articleMapper->getFitSqrSum();

        $normValue = sqrt($sqrSum);
        return $normValue;
    }

    public function updateTempFitnessValue($value) {
        $pageMapper = new PageMapper();
        $res = $pageMapper->updateTempFitValue($this->pageId, $value);

        return $res;
    }

    public function normalizeFitness($fitNormVal) {
        
    }

    public function getPageId() {
        return $this->pageId;
    }

    public function getId() {
        return $this->id;
    }

    public function getFitness() {
        return $this->pageFitnessValue;
    }

    public function getTempFitness() {
        return $this->pageTempFitnessValue;
    }

}
