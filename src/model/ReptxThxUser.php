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
        $this->id = null;
        $this->userId = null;
        $this->userReputationValue = null;
        $this->userCreditValue = null;
        $this->userTempReputationValue = null;
        $this->userTempCreditValue = null;
        $this->lastRepUpdateTimestamp = null;
        $this->lastCredUpdateTimestamp = null;
    }

    function __toString() {
        return "ReptxThx_User(id={$this->id}; "
                . "userId={$this->userId}; "
                . "userReputationValue={$this->userReputationValue}; "
                . "userCreditValue={$this->userCreditValue}; "
                . "lastRepUpdateTimestamp={$this->lastRepUpdateTimestamp}; "
                . "lastCredUpdateTimestamp={$this->lastCredUpdateTimestamp})";
    }

    /**
     * Creates a new user
     * @param type $userId
     * @param type $userReputationValue
     * @param type $userCreditValue
     * @param type $lastRepUpdateTimestamp
     * @param type $lastCredUpdateTimestamp
     * @return \ReptxThxUser
     * @throws ReadOnlyError
     * @throws MWException
     */
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

    /**
     * Inserts a user into database
     * @return type
     */
    protected function insert() {
        $userMapper = new UserMapper();
        return $userMapper->insert($this);
    }

    /**
     * loads a user given a db row
     * @param type $row
     */
    public function loadFromRow($row) {
        if ($row) {
            $this->id = $row->reptxthx_user_id;
            $this->userId = $row->user_id;

            $this->userReputationValue = $row->user_rep_value;
            $this->userCreditValue = $row->user_cred_value;
            $this->lastRepUpdateTimestamp = $row->user_last_rep_timestamp;
            $this->lastCredUpdateTimestamp = $row->user_last_cred_timestamp;

            $this->userTempReputationValue = $row->user_temp_rep_value;
            $this->userTempCreditValue = $row->user_temp_cred_value;
        }
    }

    /**
     * loads a user given an userId
     * @param type $id
     */
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

    /**
     * Returns a new user given a db row
     * @param type $row
     * @return \ReptxThxUser
     */
    public static function newFromRow($row) {
        $obj = new ReptxThxUser();
        $obj->loadFromRow($row);
        return $obj;
    }

    /**
     * Returns a new user given its userID
     * @param type $userId
     * @return \ReptxThxUser
     */
    public static function newFromId($userId) {
        $obj = new ReptxThxUser();
        $obj->loadFromID($userId);
        return $obj;
    }

    /**
     * Convert an entity's property to array
     * @return type
     */
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

    /**
     * Returns 250 user objects which reptxthx_user_id is
     * more than $last 
     * @param type $last
     * @return array
     */
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

    /**
     * Inserts all user that are not inserted into reptxthx db tables
     */
    public static function insertNewUsers() {
        $userMapper = new UserMapper();
        $newUsers = $userMapper->getNewUsers();

        $defRepVal = self::getDefaultReputationValue();
        $defCredVal = self::getDefaultCreditValue();
        while (!empty($newUsers)) {
            foreach ($newUsers as $user) {
                self::create($user['user_id'], $defRepVal, $defCredVal);
                error_log("newUser " . $user['user_id']);
            }

            $newUsers = $userMapper->getNewUsers();
        }
    }

    /**
     * Returns the default reputation value for new inserted users
     * @return type
     */
    public static function getDefaultReputationValue() {
        $userMapper = new UserMapper();
        $numUsers = $userMapper->getWikiUsersNumber();

        return 1 / sqrt($numUsers);
    }

    /**
     * Returns the default credit value for new inserted users
     * @return int
     */
    public static function getDefaultCreditValue() {
        return 0;
    }

    /**
     * Returns reputation average
     * @return type
     */
    public static function getReputationAvg() {
        $userMapper = new UserMapper();
        $avgRep = $userMapper->getReputationAvg();

        return $avgRep;
    }

    /**
     * Returns creadit average
     * @return type
     */
    public static function getCreditAvg() {
        $userMapper = new UserMapper();
        $avgRep = $userMapper->getCreditAvg();

        return $avgRep;
    }

    /**
     * Returns reputation sum
     * @return type
     */
    public static function getReputationSum() {
        $userMapper = new UserMapper();
        $RepSum = $userMapper->getReputationSum();

        return $RepSum;
    }

    /**
     * Returns credit sum
     * @return type
     */
    public static function getCreditSum() {
        $userMapper = new UserMapper();
        $credSum = $userMapper->getCreditSum();

        return $credSum;
    }

    /**
     * Updates temporary reputation value
     * @param type $value
     * @return type
     */
    public function updateTempRepValue($value) {
        $userMapper = new UserMapper();
        $res = $userMapper->updateTempRepValue($this->userId, $value);

        return $res;
    }

    /**
     * Updates temporary credit value
     * @param type $value
     * @return type
     */
    public function updateTempCredValue($value) {
        $userMapper = new UserMapper();
        $res = $userMapper->updateTempCredValue($this->userId, $value);

        return $res;
    }

    /**
     * Returns a value used for normalization of reputation
     * @return type
     */
    public static function getRepNormValue() {
        $userMapper = new UserMapper();
        $sqrSum = $userMapper->getRepSqrSum();

        $normValue = sqrt($sqrSum);
        return $normValue;
    }

    /**
     * Returns a value used for normalization of credit
     * @return type
     */
    public static function getCredNormValue() {
        $userMapper = new UserMapper();
        $sqrSum = $userMapper->getCredSqrSum();

        $normValue = sqrt($sqrSum);
        return $normValue;
    }

    /**
     * Normalizes reputation values
     * @param type $normValue
     */
    public function normalizeReputation($normValue) {
        $userMapper = new UserMapper();
        $normalizedRep = $this->userTempReputationValue / $normValue;
        $userMapper->updateTempRepValue($this->userId, $normalizedRep);
    }

    /**
     * Normalizes credit values
     * @param type $normValue
     */
    public function normalizeCredit($normValue) {
        $userMapper = new UserMapper();
        $normalizedCred = $this->userTempCreditValue / $normValue;
        $userMapper->updateTempCredValue($this->userId, $normalizedCred);
    }

    /**
     * Copies temporary reputation value into reputation column
     */
    public function commitReputation() {
        $userMapper = new UserMapper();

        $now = wfTimestampNow();

        $userMapper->updateRepValue($this->userId, $this->userTempReputationValue, $now);
    }

    /**
     * Copies credit reputation value into credit column
     */
    public function commitCredit() {
        $userMapper = new UserMapper();

        $now = wfTimestampNow();

        $userMapper->updateCredValue($this->userId, $this->userTempCreditValue, $now);
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

    public function getRepLastUpdatedTimestamp() {
        return $this->lastRepUpdateTimestamp;
    }

    public function getCredLastUpdatedTimestamp() {
        return $this->lastCredUpdateTimestamp;
    }

}
