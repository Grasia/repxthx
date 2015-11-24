<?php

class Interaction extends AbstractModelElement {

    protected $id = null;
    protected $type = null;
    protected $sender = null;
    protected $recipient = null;
    protected $page_id = null;
    protected $timestamp = null;

    protected function __construct() {
        
    }

    function __toString() {
        return "ReptxThx_Interaction(id={$this->id}; "
                . "type={$this->type}; "
                . "sender={$this->sender}; "
                . "recipient={$this->recipient}; "
                . "page_id={$this->page_id}; "
                . "timestamp={$this->timestamp})";
    }

    /**
     * Creates a new interaction.
     * @param type $type
     * @param type $sender
     * @param type $recipient
     * @param type $page_id
     * @return \Interaction
     * @throws ReadOnlyError
     * @throws MWException
     */
    public static function create($type, $sender, $recipient, $page_id) {

        if (wfReadOnly()) {
            throw new ReadOnlyError();
        }

        $obj = new Interaction;

        if (empty($type)) {
            throw new MWException("'type' parameter missing");
        }

        if (empty($sender)) {
            throw new MWException("'sender' parameter missing");
        }

        if (empty($page_id)) {
            throw new MWException("'page_id' parameter missing");
        }

        $obj->id = false;
        $obj->type = $type;
        $obj->sender = $sender;
        $obj->recipient = $recipient;
        $obj->page_id = $page_id;
        $obj->timestamp = wfTimestampNow();

        $obj->insert();

        self::tryInsertJob();
        return $obj;
    }

    /**
     * Inserts a new job for reptxthx algorithm execution
     * @global type $executionMinutes
     * @global type $executionInteractionCount
     */
    private function tryInsertJob() {
        global $executionMinutes, $executionInteractionCount;

        $interactionCurrentCount = ReptxThxProperties::getInteractionCount();
        $interactionCurrentCount = intval($interactionCurrentCount);
        error_log("interactionCount : $interactionCurrentCount");

        ReptxThxProperties::setInteractionCount($interactionCurrentCount + 1);
        if ($interactionCurrentCount >= $executionInteractionCount) {
            error_log("interactionCurrentCount more than configured value");

            $queue = JobQueueGroup::singleton();
            $algorithmJobSize = $queue->get('executeReptxThxAlgorithm')->getSize();

            if ($algorithmJobSize == 0) {
                error_log("empty queue");
                print_r("insertJob");
                $jobParams = array();
                $title = Title::newMainPage();

                $job = new ReptxThxAlgorithmJob($title, $jobParams);

                $queue->push($job);
                error_log("job inserted");
            }
        }
    }

    /**
     * Convert an entity's property to array
     * @return type
     */
    public function toDbArray() {

        $data = array(
            'interaction_type' => $this->type,
            'interaction_sender_id' => $this->sender,
            'interaction_recipient_id' => $this->recipient,
            'interaction_page_id' => $this->page_id,
            'interaction_timestamp' => $this->timestamp
        );
        if ($this->id) {
            $data['interaction_id'] = $this->id;
        }

        return $data;
    }

    /**
     * Inserts new interaction into db
     * @return type
     */
    protected function insert() {
        $interactionMapper = new InteractionMapper();
        return $interactionMapper->insert($this);
    }

    /**
     * loads an interation given a db row
     * @param type $row
     */
    public function loadFromRow($row) {
        $this->id = $row->interaction_id;
        $this->type = $row->interaction_type;

        $this->sender = $row->interaction_sender_id;
        $this->recipient = $row->interaction_recipient_id;
        $this->page_id = $row->interaction_page_id;
        $this->timestamp = $row->interaction_timestamp;
    }

    /**
     * loads an interaction given an id
     * @param type $id
     */
    public function loadFromID($id) {
        $interactionMapper = new InteractionMapper();
        $interaction = $interactionMapper->getById($id);

        $this->id = $interaction->id;
        $this->type = $interaction->type;
        $this->sender = $interaction->sender;
        $this->recipient = $interaction->recipient;
        $this->page_id = $interaction->page_id;
        $this->timestamp = $interaction->timestamp;
    }

    /**
     * Returns new interaction given a db row
     * @param type $row
     * @return \Interaction
     */
    public static function newFromRow($row) {
        $obj = new Interaction();
        $obj->loadFromRow($row);
        return $obj;
    }

    /**
     * Returns new interaction given an id
     * @param type $id
     * @return \Interaction
     */
    public static function newFromID($id) {
        $obj = new Interaction();
        $obj->loadFromID($id);
        return $obj;
    }

    /**
     * Returns the sum of given and received 
     * @param type $userId
     * @return type
     */
    public static function getUserThankDegree($userId) {
        $interactionMapper = new InteractionMapper();
        $degree = $interactionMapper->getUserDegree($userId);

        return $degree;
    }

    /**
     * Returns the number of pages a user created or revised 
     * @param type $userId
     * @return type
     */
    public static function getUserCreationDegree($userId) {
        $interactionMapper = new InteractionMapper();
        $degree = $interactionMapper->getUserCreationDegree($userId);

        return $degree;
    }

    /**
     * Returns the number of thanks given through a given page
     * @param type $pageId
     * @return type
     */
    public static function getPageThanksDegree($pageId) {
        $interactionMapper = new InteractionMapper();
        $degree = $interactionMapper->getPageThanksDegree($pageId);

        return $degree;
    }

    /**
     * Returns the number of user that created of revised a given page
     * @param type $pageId
     * @return type
     */
    public static function getPageCretionDegree($pageId) {
        $interactionMapper = new InteractionMapper();
        $degree = $interactionMapper->getPageCreationDegree($pageId);

        return $degree;
    }

    /**
     * Returns an array with all the users that created page
     * @param type $pageId
     * @return array
     */
    public static function getPageCreatingUsers($pageId) {
        $data = array();
        $interactionMapper = new InteractionMapper();
        $userIdList = $interactionMapper->getPageCreatingUser($pageId);
        foreach ($userIdList as $user) {

            $user = ReptxThxUser::newFromId($user->interaction_sender_id);
            array_push($data, $user);
        }
        return $data;
    }

    /**
     * Returns an array with the all the users that received a thank
     * through a given page
     * @param type $pageId
     * @return array
     */
    public static function getPageThanksReceived($pageId) {
        $data = array();
        $interactionMapper = new InteractionMapper();
        $userIdList = $interactionMapper->getPageThanksReceived($pageId);

        foreach ($userIdList as $user) {

            $user = ReptxThxUser::newFromId($user->interaction_sender_id);
            array_push($data, $user);
        }
        return $data;
    }

    /**
     * Returns an array with the all the users that gave a thank through 
     * a given page
     * @param type $pageId
     * @return array
     */
    public static function getPageThanksGiven($pageId) {
        $data = array();
        $interactionMapper = new InteractionMapper();
        $userIdList = $interactionMapper->getPageThanksGiven($pageId);

        foreach ($userIdList as $user) {

            $user = ReptxThxUser::newFromId($user->interaction_recipient_id);
            array_push($data, $user);
        }
        return $data;
    }

    /**
     * Returns an array with the all the pages created or  by a user
     * @param type $userId
     * @return array
     */
    public static function getUserCreatedArticles($userId) {
        $data = array();
        $interactionMapper = new InteractionMapper();
        $articleIdList = $interactionMapper->getUserCreatedPages($userId);

        foreach ($articleIdList as $article) {

            $article = ReptxThxPage::newFromId($article->interaction_page_id);
            array_push($data, $article);
        }
        return $data;
    }

    /**
     * Returns an array with the pageId of all the pages through a user
     * @param type $pageId
     * @return array
     */
    public static function getUserThanksReceived($pageId) {
        $data = array();
        $interactionMapper = new InteractionMapper();
        $articleIdList = $interactionMapper->getUserThanksReceived($pageId);

        foreach ($articleIdList as $article) {

            $article = ReptxThxPage::newFromId($article->interaction_page_id);
            array_push($data, $article);
        }
        return $data;
    }

    /**
     * Returns an array with all the pages through a user
     * gave thanks. 
     * @param type $userId
     * @return array
     */
    public static function getUserThanksGiven($userId) {
        $data = array();
        $interactionMapper = new InteractionMapper();
        $articleIdList = $interactionMapper->getUserThanksGiven($userId);

        foreach ($articleIdList as $article) {

            $article = ReptxThxPage::newFromId($article->interaction_page_id);
            array_push($data, $article);
        }
        return $data;
    }

    /**
     * Gets all the revisions and creations of pages not inserted into
     * reptxthx db tables
     */
    public static function insertNewPageCreations() {
        $interactionMapper = new InteractionMapper();
        $newInteractions = $interactionMapper->getNewCreations();

        while (!empty($newInteractions)) {
            foreach ($newInteractions as $interaction) {
                self::create(2, $interaction['user_id'], null, $interaction['page_id']);
            }

            $newInteractions = $interactionMapper->getNewCreations();
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getType() {
        return $this->type;
    }

    public function getSender() {
        return $this->sender;
    }

    public function getRecipient() {
        return $this->recipient;
    }

    public function getPageId() {
        return $this->page_id;
    }

    public function getTimestamp() {
        return $this->getTimestamp;
    }

}
