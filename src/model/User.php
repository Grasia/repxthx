<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ReptxThx_User extends AbstractModelElement {

    protected $id;
    protected $userId;
    protected $userReputationValue;
    protected $userCreditValue;
    protected $lastRepUpdateTimestamp;
    protected $lastCredUpdateTimestamp;

    protected function __construct() {
        
    }

    function __toString() {
        return "ReptxThx_User(id={$this->id}; "
                . "userId={$this->userId}; "
                . "userReputationValue={$this->userReputationValue}; "
                . "userCreditValue={$this->userCreditValue}; "
                . "lastRepUpdateTimestamp={$this->lastRepUpdateTimestamp}; "
                . "lastCredUpdateTimestamp={$this->lastCredUpdateTimestamp})";
    }

    public static function create($userId, $userReputationValue, $userCreditValue, $lastRepUpdateTimestamp = "", $lastCredUpdateTimestamp = "") {
        if (wfReadOnly()) {
            throw new ReadOnlyError();
        }

        $obj = new ReptxThx_User;

        if (empty($userId)) {
            throw new MWException("'type' parameter missing");
        }

        if (!isset($userReputationValue)) {
            throw new MWException("'sender' parameter missing");
        }

        if (!isset($userCreditValue)) {
            throw new MWException("'recipient' parameter missing");
        }

        if (empty($lastRepUpdateTimestamp)) {
            $obj->lastRepUpdateTimestamp = wfTimestampNow();
        } else {
            $obj->lastRepUpdateTimestamp = $lastRepUpdateTimestamp;
        }

        if (empty($lastCredUpdateTimestamp)) {
            $obj->lastCredUpdateTimestamp = wfTimestampNow();
        } else {
            $obj->lastCredUpdateTimestamp = $lastCredUpdateTimestamp;
        }

        $obj->id = false;
        $obj->userId = $userId;
        $obj->userReputationValue = $userReputationValue;
        $obj->userCreditValue = $userCreditValue;

        $obj->insert();

        return $obj;
    }

    protected function insert() {
        $userMapper = new UserMapper();
        return $userMapper->insert($this);
    }

    public function loadFromRow($row) {
        $this->id = $row->reptxthx_user_id;
        $this->userId = $row->user_id;

        $this->userReputationValue = $row->user_rep_value;
        $this->userCreditValue = $row->user_cred_value;
        $this->lastRepUpdateTimestamp = $row->user_last_rep_timestamp;
        $this->lastCredUpdateTimestamp = $row->user_last_cred_timestamp;
    }

    public function loadFromID($id) {
        $userMapper = new UserMapper();
        $user = $interactionMapper->getById($id);

        // Copy over the attribute
        $this->id = $user->id;
        $this->type = $user->type;
        $this->sender = $user->sender;
        $this->recipient = $user->recipient;
        $this->page_id = $user->page_id;
        $this->timestamp = $user->timestamp;
    }

    public static function newFromRow($row) {
        $obj = new ReptxThx_User();
        $obj->loadFromRow($row);
        return $obj;
    }

    public function toDbArray() {
        $data = array(
            'user_id' => $this->userId,
            'user_rep_value' => $this->userReputationValue,
            'user_cred_value' => $this->userCreditValue,
            'user_last_rep_timestamp' => $this->lastRepUpdateTimestamp,
            'user_last_cred_timestamp' => $this->lastCredUpdateTimestamp
        );
        if ($this->id) {
            $data['reptxthx_user_id'] = $this->id;
        }

        return $data;
    }

    public static function getAllUsers(&$continuation) {

        $userMapper = new UserMapper();
        return $userMapper->getUsersArray(1000, $continuation);
    }

    public static function insertAllUsers() {
        $userMapper = new UserMapper();
        return $userMapper->insertAllUsers();
    }

}
