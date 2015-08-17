<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UserMapper extends AbstractMapper {

    /**
     * Insert an event record
     *
     * @param EchoEvent
     * @return int|bool
     */
    const USER_TABLE_NAME = 'reptxThx_user';

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
     * Create an EchoEvent by id
     *
     * @param int
     * @param boolean
     * @return EchoEvent
     * @throws MWException
     */
    public function getByUserId($id) {

        $db = $this->dbFactory->getForRead();

        $row = $db->selectRow('reptxThx_user', '*', array('user_id' => $id), __METHOD__);

        if (!$row) {
            throw new MWException("No Users found with ID: $id");
        }

        return ReptxThxUser::newFromRow($row);
    }

    public function getUsersArray($limit, $last) {
        $data = array();
        $db = $this->dbFactory->getForRead();

        $res = $db->select('reptxthx_user', '*', "reptxthx_user_id > $last", __METHOD__, array('LIMIT' => $limit, 'ORDER BY' => 'reptxThx_user_id'));

        for ($i = 0; $i < $res->numRows(); $i++) {
            array_push($data, $res->next());
        }

        return $data;
    }

    public function getWikiUsersArray($limit, $continuation) {
        
    }

    public function getWikiUsersNumber() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('user', array('numUsers' => 'COUNT(*)'), '', __METHOD__);

        return $res->numUsers;
    }

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

    public function updateTempRepValue($userId, $value) {

        $db = $this->dbFactory->getForWrite();

        $res = $db->update('reptxthx_user', array('user_temp_rep_value' => $value), array('user_id' => $userId), __METHOD__);

        return $res;
    }

    public function updateRepValue($userId, $repVal, $timestamp) {
        $db = $this->dbFactory->getForWrite();

        $res = $db->update('reptxthx_user', array('user_rep_value' => $repVal, 'user_last_rep_timestamp' => $timestamp), array('user_id' => $userId), __METHOD__);

        return $res;
    }

    public function updateTempCredValue($userId, $value) {
        $db = $this->dbFactory->getForWrite();

        $res = $db->update('reptxthx_user', array('user_temp_cred_value' => $value), array('user_id' => $userId), __METHOD__);

        return $res;
    }

    public function updateCredValue($userId, $value, $timestamp) {
        $db = $this->dbFactory->getForWrite();

        $res = $db->update('reptxthx_user', array('user_cred_value' => $value, 'user_last_rep_timestamp' => $timestamp), array('user_id' => $userId), __METHOD__);

        return $res;
    }

    public function getRepSqrSum() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_user', array('sqrSum' => 'SUM(user_temp_rep_value * user_temp_rep_value)'), '', __METHOD__);

        return $res->sqrSum;
    }

    public function getCredSqrSum() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_user', array('sqrSum' => 'SUM(user_temp_cred_value * user_temp_cred_value)'), '', __METHOD__);

        return $res->sqrSum;
    }

    public function getReputationAvg() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_user', array('repAvg' => 'avg(user_temp_rep_value)'), '', __METHOD__);

        return $res->repAvg;
    }

    public function getCreditAvg() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_user', array('credAvg' => 'avg(user_temp_cred_value)'), '', __METHOD__);

        return $res->credAvg;
    }

    public function getReputationSum() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_user', array('repSum' => 'sum(user_temp_rep_value)'), '', __METHOD__);

        return $res->repSum;
    }

    public function getCreditSum() {
        $db = $this->dbFactory->getForRead();
        $res = $db->selectRow('reptxthx_user', array('credSum' => 'avg(user_temp_cred_value)'), '', __METHOD__);

        return $res->credSum;
    }

}
