<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class InteractionMapper extends AbstractMapper {

    /**
     * Insert an event record
     *
     * @param EchoEvent
     * @return int|bool
     */
    const INTERACTION_TABLE_NAME = 'reptxThx_interaction';

    public function insert(Interaction $interaction) {

        if ($interaction->getType() == 2 && self::existsCreation($interaction)) {
            return true;
        }

        $dbw = $this->dbFactory->getForWrite();

        $id = $dbw->nextSequenceValue('reptxThx_interaction_id');
        error_log($id);
        if ($id) {
            $row['interaction_id'] = $id;
        }
        $row = $interaction->toDbArray();
        $res = $dbw->insert('reptxThx_interaction', $row, __METHOD__, array('IGNORE'));

        if ($res) {
            $id = $dbw->insertId();
            return $id;
        } else {
            return false;
        }
    }

    public function existsCreation(Interaction $interaction) {
        $db = $this->dbFactory->getForRead();
        error_log("existsCreation");
        $row = $db->selectRow('reptxThx_interaction', '*', array('interaction_sender_id' => $interaction->getSender(), 'interaction_type' => 2, 'interaction_page_id' => $interaction->getPageId()), __METHOD__);

        if ($row == FALSE) {
            error_log("existsCreation false");
            return FALSE;
        } else {
            error_log("existsCreation true");
            return TRUE;
        }
    }

    public function getUserDegree($userId) {
        $db = $this->dbFactory->getForRead();

        $res = $db->selectRow('reptxThx_interaction', array('userDegree' => 'COUNT(*)'), "interaction_sender_id = $userId OR interaction_recipient_id = $userId", __METHOD__);

        return $res->userDegree;
    }

    public function getUserCreationDegree($userId) {
        $db = $this->dbFactory->getForRead();

        $res = $db->selectRow('reptxThx_interaction', array('userDegree' => 'COUNT(*)'), array('interaction_sender_id' => $userId, 'interaction_type' => 2), __METHOD__);

        return $res->userDegree;
    }

    public function getPageDegree($pageId) {
        $db = $this->dbFactory->getForRead();

        $res = $db->selectRow('reptxThx_interaction', array('pageDegree' => 'COUNT(*)'), array('interaction_page_id' => $pageId), __METHOD__);

        return $res->pageDegree;
    }

    public function getPageCreationDegree($pageId) {
        $db = $this->dbFactory->getForRead();

        $res = $db->selectRow('reptxThx_interaction', array('pageDegree' => 'COUNT(*)'), array('interaction_page_id' => $pageId, 'interaction_type' => 2), __METHOD__);

        return $res->pageDegree;
    }

    public function getUserCreatedPages($userId) {
        $data = array();
        $db = $this->dbFactory->getForRead();

        $res = $db->select('reptxThx_interaction', 'interaction_page_id', array('interaction_type' => 2, 'interaction_sender_id' => $userId), __METHOD__);

        for ($i = 0; $i < $res->numRows(); $i++) {
            array_push($data, $res->next());
        }

        return $data;
    }

    public function getUserThanksReceived($userId) {
        $data = array();
        $db = $this->dbFactory->getForRead();

        $res = $db->select('reptxThx_interaction', 'interaction_page_id', array('interaction_type' => 1, 'interaction_recipient_id' => $userId), __METHOD__);

        for ($i = 0; $i < $res->numRows(); $i++) {
            array_push($data, $res->next());
        }

        return $data;
    }

    public function getUserThanksGiven($userId) {
        $data = array();
        $db = $this->dbFactory->getForRead();

        $res = $db->select('reptxThx_interaction', 'interaction_page_id', array('interaction_type' => 1, 'interaction_sender_id' => $userId), __METHOD__);

        for ($i = 0; $i < $res->numRows(); $i++) {
            array_push($data, $res->next());
        }

        return $data;
    }

    public function getPageCreatingUser($pageId) {
        $data = array();
        $db = $this->dbFactory->getForRead();

        $res = $db->select('reptxThx_interaction', 'interaction_sender_id', array('interaction_type' => 2, 'interaction_page_id' => $pageId), __METHOD__);

        for ($i = 0; $i < $res->numRows(); $i++) {
            array_push($data, $res->next());
        }

        return $data;
    }

    public function getPageThanksReceived($pageId) {
        $data = array();
        $db = $this->dbFactory->getForRead();

        $res = $db->select('reptxThx_interaction', 'interaction_sender_id', array('interaction_type' => 1, 'interaction_page_id' => $pageId), __METHOD__);

        for ($i = 0; $i < $res->numRows(); $i++) {
            array_push($data, $res->next());
        }

        return $data;
    }

    public function getPageThanksGiven($pageId) {
        $data = array();
        $db = $this->dbFactory->getForRead();

        $res = $db->select('reptxThx_interaction', 'interaction_recipient_id', array('interaction_type' => 1, 'interaction_page_id' => $pageId), __METHOD__);

        for ($i = 0; $i < $res->numRows(); $i++) {
            array_push($data, $res->next());
        }

        return $data;
    }

    public function getById($id) {
        $db = $this->dbFactory->getForRead(DB_MASTER);

        $row = $db->selectRow('reptxthx_interaction', '*', array('interaction_id' => $id), __METHOD__);

        if (!$row) {
            throw new MWException("No Interaction found with ID: $id");
        }

        return Interaction::newFromRow($row);
    }

}
