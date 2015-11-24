<?php

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

    /**
     * Creates a new page
     * @param type $pageId
     * @param type $pageFitnessValue
     * @param type $lastFitnessUpdateTimestamp
     * @return \ReptxThxPage
     * @throws ReadOnlyError
     * @throws MWException
     */
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

    /**
     * Returns a new page given its id 
     * @param type $page_id
     * @return \ReptxThxPage
     */
    public static function newFromId($page_id) {
        $obj = new ReptxThxPage();
        $obj->loadFromID($page_id);
        return $obj;
    }

    /**
     * Inserts a new Page object into database 
     * @return type
     */
    protected function insert() {
        $articleMapper = new PageMapper();
        return $articleMapper->insert($this);
    }

    /**
     * loads a page given a db row
     * @param type $row
     */
    public function loadFromRow($row) {
        $this->id = $row->reptxthx_page_id;
        $this->pageId = $row->page_id;

        $this->pageFitnessValue = $row->page_fitness_value;
        $this->pageTempFitnessValue = $row->page_temp_fitness_value;
        $this->lastFitnessUpdateTimestamp = $row->page_last_fitness_timestamp;
    }

    /**
     * loads a page given a pageId
     * @param type $pageId
     */
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

    /**
     * Retuns a new ReptxThxPage object given a db row
     * @param type $row
     * @return \ReptxThxPage
     */
    public static function newFromRow($row) {
        $obj = new ReptxThxPage();
        $obj->loadFromRow($row);
        return $obj;
    }

    /**
     * Convert an entity's property to array
     * @return type
     */
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

    /**
     * Returns 250 page objects which reptxthx_page_id is
     * more than $last
     * @param type $last
     * @return array
     */
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

    /**
     * Inserts all pages that are not inserted into reptxthx db tables
     */
    public static function insertNewPages() {
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

    /**
     * Returns the default fitness value for new inserted pages
     * @return type
     */
    public static function getDefaultFitnessValue() {
        $articleMapper = new PageMapper();
        $numArticles = $articleMapper->getWikPageNumber();

        return 1 / sqrt($numArticles);
    }

    /**
     * Returns fitness average value
     * @return type
     */
    public static function getFitnessAvg() {
        $articleMapper = new PageMapper();
        $avgArticles = $articleMapper->getFitnessAvg();

        return $avgArticles;
    }

    /**
     * Returns a value used for normalization of fitness
     * values.
     * @return type
     */
    public static function getFitNormValue() {
        $articleMapper = new PageMapper();
        $sqrSum = $articleMapper->getFitSqrSum();

        $normValue = sqrt($sqrSum);
        return $normValue;
    }

    /**
     * Returns fitness sum
     * @return type
     */
    public static function getFitnessSum() {
        $articleMapper = new PageMapper();
        $fitnessSum = $articleMapper->getFitnessSum();

        return $fitnessSum;
    }

    /**
     * Normalizes all fitness values
     * @param type $normValue
     */
    public function normalizeFitness($normValue) {
        $articleMapper = new PageMapper();
        $normalizedCred = $this->pageTempFitnessValue / $normValue;
        $articleMapper->updateTempFitValue($this->pageId, $normalizedCred);
    }

    /**
     * updates fitness temporary value
     * @param type $value
     * @return type
     */
    public function updateTempFitnessValue($value) {
        $pageMapper = new PageMapper();
        $res = $pageMapper->updateTempFitValue($this->pageId, $value);

        return $res;
    }

    /**
     * Copies the fitness temporary value into the fitness column.
     */
    public function commitFitness() {
        $pageMapper = new PageMapper();

        $now = wfTimestampNow();

        $pageMapper->updateFitnessValue($this->pageId, $this->pageTempFitnessValue, $now);
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

    public function getLastFitnessUpdateTimestamp() {
        return $this->lastFitnessUpdateTimestamp;
    }

}
