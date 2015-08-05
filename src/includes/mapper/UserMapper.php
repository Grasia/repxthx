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

    public function insert(ReptxThx_User $user) {
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

        return Interaction::newFromRow($row);
    }

    public function getUsersArray($limit, $continuation) {
        $data = array();
        $db = $this->dbFactory->getForRead();
        $condition = "";
        if ($continuation == "") {
            $condition = "reptxthx_user_id>= 0";
        } else {
            $condition = "reptxthx_user_id>=" . $continuation;
        }

        $res = $db->select('user', '*', $condition, __METHOD__, array('LIMIT' => $limit, 'ORDER BY' => 'reptxThx_user_id'));

        foreach ($res as $userRow) {
            array_push($data, ReptxThx_User::newFromRow($userRow));
        }

        return $data;
    }

    public function getWikiUsersArray($limit, $continuation) {
        
    }

    public function insertAllUsers() {

        $db = $this->dbFactory->getForRead();
        $defaultReputationValue = 0;
        $defaultCreditValue = 0;

        $res = $db->select('user', 'user_id', '', __METHOD__);

        while ($row = $res->fetchRow()) {
            print_r($row);
            ReptxThx_User::create($row["user_id"], $defaultReputationValue, $defaultCreditValue);
        }
    }

}
