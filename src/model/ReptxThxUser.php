<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ReptxThxUser extends AbstractModelElement {

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

        $obj = new ReptxThxUser;

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
        $user = $userMapper->getById($id);

        // Copy over the attribute
        $this->id = $user->id;
        $this->userId = $user->userId;
        $this->userReputationValue = $user->userReputationValue;
        $this->userCreditValue = $user->userCreditValue;
        $this->lastRepUpdateTimestamp = $user->lastRepUpdateTimestamp;
        $this->lastCredUpdateTimestamp = $user->lastCredUpdateTimestamp;
    }

    public static function newFromRow($row) {
        $obj = new ReptxThxUser();
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

    public static function getUsersChunk(&$last) {

        $data = array();

        $userMapper = new UserMapper();
        $usersChunk = $userMapper->getUsersArray(250, $last);

        foreach ($usersChunk as $userRow) {
            array_push($data, self::newFromRow($userRow));
        }
        if (!empty($data)) {
            $last = end($data)->getId();
        }
        return $data;
    }
    
    public static function insertNewUsers() {
        $userMapper = new UserMapper();
        $newUsers = $userMapper->getNewUsers();

        $defRepVal = self::getDefaultReputationValue();
        $defCredVal = self::getDefaultCreditValue();
        while (!empty($newUsers)) {
            foreach ($newUsers as $user) {
                self::create($user['user_id'], $defRepVal, $defCredVal);
            }

            $newUsers = $userMapper->getNewUsers();
        }
    }

    public static function getDefaultReputationValue() {
        $userMapper = new UserMapper();
        $numUsers = $userMapper->getWikiUsersNumber();

        return 1 / sqrt($numUsers);
    }

    public static function getDefaultCreditValue() {
        return 0;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getId() {
        return $this->id;
    }

}
