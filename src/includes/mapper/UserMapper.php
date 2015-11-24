<?php

class UserMapper extends AbstractMapper {

    /**
     * 
     * @param ReptxThxUser $user
     * @return boolean
     */
    public function insert(ReptxThxUser $user) {
        $dbw = $this->dbFactory->getForWrite();

        $id = $dbw->nextSequenceValue('reptxThx_user_id');

        if ($id) {
            $row['user_id'] = $id;
        }

        $row = $user->toDbArray();

        $res = $dbw->insert('reptxThx_user', $row, __METHOD__);
        if ($res) {
            $id = $dbw->insertId();
            return $id;
        } else {
            return false;
        }
    }

    /**
     * gets a user by its userId
     * @param type $id
     * @return type
     */
    public function getByUserId($id) {

        $db = $this->dbFactory->getForRead();

        $row = $db->selectRow('reptxThx_user', '*', array('user_id' => $id), __METHOD__);

        return ReptxThxUser::newFromRow($row);
    }

    /**
     * Gets an array with $limit number of users
     * @param type $limit
     * @param type $last
     * @return array
     */
    public function getUsersArray($limit, $last) {
        $data = array();
        $db = $this->dbFactory->getForRead();

        $res = $db->select('reptxthx_user', '*', "reptxthx_user_id > $last", __METHOD__, array('LIMIT' => $limit, 'ORDER BY' => 'reptxThx_user_id'));

        for ($i = 0; $i < $res->numRows(); $i++) {
            array_push($data, $res->next());
        }

        return $data;
    }

    /**
     * Returns the number of wiki users
     * @return type
     */
    public function getWikiUsersNumber() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('user', array('numUsers' => 'COUNT(*)'), '', __METHOD__);

        return $res->numUsers;
    }

    /**
     * Returns an array with all the users not inserted into
     * reptxths tables.
     * @return array
     */
    public function getNewUsers() {
        $data = array();
        $db = $this->dbFactory->getForRead();
        $limit = 250;

        $res = $db->select(array('mediawikiUsers' => 'user', 'extensionUsers' => 'reptxthx_user'), 'mediawikiUsers.user_id', 'extensionUsers.user_id IS NULL', __METHOD__, array('LIMIT' => $limit), array('extensionUsers' => array('LEFT JOIN', 'mediawikiUsers.user_id = extensionUsers.user_id')));

        for ($i = 0; $i < $res->numRows(); $i++) {
            array_push($data, $res->fetchRow());
        }

        return $data;
    }

    /**
     * Updates temporary reputation value 
     * @param type $userId
     * @param type $value
     * @return type
     */
    public function updateTempRepValue($userId, $value) {

        $db = $this->dbFactory->getForWrite();

        $res = $db->update('reptxthx_user', array('user_temp_rep_value' => $value), array('user_id' => $userId), __METHOD__);

        return $res;
    }

    /**
     * Updates reputation value
     * @param type $userId
     * @param type $repVal
     * @param type $timestamp
     * @return type
     */
    public function updateRepValue($userId, $repVal, $timestamp) {
        $db = $this->dbFactory->getForWrite();

        $res = $db->update('reptxthx_user', array('user_rep_value' => $repVal, 'user_last_rep_timestamp' => $timestamp), array('user_id' => $userId), __METHOD__);

        return $res;
    }

    /**
     * Updates temporary credit value 
     * @param type $userId
     * @param type $value
     * @return type
     */
    public function updateTempCredValue($userId, $value) {
        $db = $this->dbFactory->getForWrite();

        $res = $db->update('reptxthx_user', array('user_temp_cred_value' => $value), array('user_id' => $userId), __METHOD__);

        return $res;
    }

    /**
     * Updates reputation value 
     * @param type $userId
     * @param type $value
     * @param type $timestamp
     * @return type
     */
    public function updateCredValue($userId, $value, $timestamp) {
        $db = $this->dbFactory->getForWrite();

        $res = $db->update('reptxthx_user', array('user_cred_value' => $value, 'user_last_rep_timestamp' => $timestamp), array('user_id' => $userId), __METHOD__);

        return $res;
    }

    /**
     * Returns the sum of the squered reputation value
     * @return type
     */
    public function getRepSqrSum() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_user', array('sqrSum' => 'SUM(user_temp_rep_value * user_temp_rep_value)'), '', __METHOD__);

        return $res->sqrSum;
    }

    /**
     * Returns sum of the squered credit value
     * @return type
     */
    public function getCredSqrSum() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_user', array('sqrSum' => 'SUM(user_temp_cred_value * user_temp_cred_value)'), '', __METHOD__);

        return $res->sqrSum;
    }

    /**
     * Returns reputation average
     * @return type
     */
    public function getReputationAvg() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_user', array('repAvg' => 'avg(user_temp_rep_value)'), '', __METHOD__);

        return $res->repAvg;
    }

    /**
     * Returns credit average
     * @return type
     */
    public function getCreditAvg() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_user', array('credAvg' => 'avg(user_temp_cred_value)'), '', __METHOD__);

        return $res->credAvg;
    }

    /**
     * Returns reputation sum
     * @return type
     */
    public function getReputationSum() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_user', array('repSum' => 'sum(user_temp_rep_value)'), '', __METHOD__);

        return $res->repSum;
    }

    /**
     * Returns credit sum
     * @return type
     */
    public function getCreditSum() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_user', array('credSum' => 'avg(user_temp_cred_value)'), '', __METHOD__);

        return $res->credSum;
    }

}
