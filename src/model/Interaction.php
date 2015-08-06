<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Interaction extends AbstractModelElement {

    protected $id = null;
    protected $type = null;
    protected $sender = null;
    protected $recipient = null;
    protected $page_id = null;
    protected $timestamp = null;

    /**
     * RECOMENTAR
     * You should not call the constructor.
     * Instead use one of the factory functions:
     * EchoEvent::create        To create a new event
     * EchoEvent::newFromRow    To create an event object from a row object
     * EchoEvent::newFromID     To create an event object from the database given its ID
     */
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
     * 
     * @staticvar array $validFields
     * @param type $type
     * @param type $sender
     * @param type $recipient
     * @param type $page_id
     * @return \Interaction
     * @throws ReadOnlyError
     * @throws MWException
     */
    public static function create($type, $sender, $recipient, $page_id) {
        // Do not create event and notifications if write access is locked
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

        error_log($obj);
        $obj->insert();

        return $obj;
    }

    /**
     * 
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
     * Inserts the object into the database.
     */
    protected function insert() {
        $interactionMapper = new InteractionMapper();
        return $interactionMapper->insert($this);
    }

    /**
     * Loads data from the provided $row into this object.
     *
     * @param $row stdClass row object from echo_event
     */
    public function loadFromRow($row) {
        $this->id = $row->interaction_id;
        $this->type = $row->interaction_type;

        $this->sender = $row->interaction_sender;
        $this->recipient = $row->interaction_recipient;
        $this->page_id = $row->interaction_pageId;
        $this->timestamp = $row->interaction_timestamp;
    }

    /**
     * Loads data from the database into this object, given the event ID.
     * @param $id int Event ID
     * @param $fromMaster bool
     */
    public function loadFromID($id) {
        $interactionMapper = new InteractionMapper();
        $interaction = $interactionMapper->getById($id);

        // Copy over the attribute
        $this->id = $interaction->id;
        $this->type = $interaction->type;
        $this->sender = $interaction->sender;
        $this->recipient = $interaction->recipient;
        $this->page_id = $interaction->page_id;
        $this->timestamp = $interaction->timestamp;
    }

    /**
     * Creates an EchoEvent from a row object
     *
     * @param $row stdClass row object from echo_event
     * @return EchoEvent object.
     */
    public static function newFromRow($row) {
        $obj = new Interaction();
        $obj->loadFromRow($row);
        return $obj;
    }

    /**
     * Creates an EchoEvent from the database by ID
     *
     * @param $id int Event ID
     * @return EchoEvent
     */
    public static function newFromID($id) {
        $obj = new Interaction();
        $obj->loadFromID($id);
        return $obj;
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
