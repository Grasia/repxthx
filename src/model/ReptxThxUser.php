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
    protected $userTempReputationValue;
    protected $userTempCreditValue;
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
        $obj->userTempReputationValue = $userReputationValue;
        $obj->userTempCreditValue = $userCreditValue;

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

        $this->userTempReputationValue = $row->user_temp_rep_value;
        $this->userTempCreditValue = $row->user_temp_cred_value;
    }

    public function loadFromID($id) {
        $userMapper = new UserMapper();
        $user = $userMapper->getByUserId($id);

        $this->id = $user->id;
        $this->userId = $user->userId;
        $this->userReputationValue = $user->userReputationValue;
        $this->userCreditValue = $user->userCreditValue;
        $this->lastRepUpdateTimestamp = $user->lastRepUpdateTimestamp;
        $this->lastCredUpdateTimestamp = $user->lastCredUpdateTimestamp;
        $this->userTempReputationValue = $user->userTempReputationValue;
        $this->userTempCreditValue = $user->userTempCreditValue;
    }

    public static function newFromRow($row) {
        $obj = new ReptxThxUser();
        $obj->loadFromRow($row);
        return $obj;
    }

    public static function newFromId($userId) {
        $obj = new ReptxThxUser();
        $obj->loadFromID($userId);
        return $obj;
    }

    public function toDbArray() {
        $data = array(
            'user_id' => $this->userId,
            'user_rep_value' => $this->userReputationValue,
            'user_cred_value' => $this->userCreditValue,
            'user_last_rep_timestamp' => $this->lastRepUpdateTimestamp,
            'user_last_cred_timestamp' => $this->lastCredUpdateTimestamp,
            'user_temp_rep_value' => $this->userTempReputationValue,
            'user_temp_cred_value' => $this->userTempCreditValue
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

    public static function getReputationAvg() {
        $userMapper = new UserMapper();
        $avgRep = $userMapper->getReputationAvg();

        return $avgRep;
    }

    public static function getCreditAvg() {
        $userMapper = new UserMapper();
        $avgRep = $userMapper->getCreditAvg();

        return $avgRep;
    }

    public function updateTempRepValue($value) {
        $userMapper = new UserMapper();
        $res = $userMapper->updateTempRepValue($this->userId, $value);

        return $res;
    }

    public function updateTempCredValue($value) {
        $userMapper = new UserMapper();
        $res = $userMapper->updateTempCredValue($this->userId, $value);

        return $res;
    }

    public static function getRepNormValue() {
        $userMapper = new UserMapper();
        $sqrSum = $userMapper->getRepSqrSum();

        $normValue = sqrt($sqrSum);
        return $normValue;
    }

    public static function getCredNormValue() {
        $userMapper = new UserMapper();
        $sqrSum = $userMapper->getCredSqrSum();

        $normValue = sqrt($sqrSum);
        return $normValue;
    }

    public function normalizeReputation($normValue) {
        $userMapper = new UserMapper();
        $normalizedRep = $this->userTempReputationValue / $normValue;
        $userMapper->normalizeReputation($normalizedRep,$this->userId);
    }

    public function normalizeCredit($normValue) {
        $userMapper = new UserMapper();
        $normalizedCred = $this->userTempCreditValue / $normValue;
        $userMapper->normalizeCredit($normalizedCred,$this->userId);
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getReputationValue() {
        return $this->userReputationValue;
    }

    public function getTempReputationValue() {
        return $this->userTempReputationValue;
    }

    public function getCreditValue() {
        return $this->userCreditValue;
    }

    public function getTempCreditValue() {
        return $this->userTempCreditValue;
    }

}
