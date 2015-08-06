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

    /**
     * Create an EchoEvent by id
     *
     * @param int
     * @param boolean
     * @return EchoEvent
     * @throws MWException
     */
    public function getById($id) {
        $db = $this->dbFactory->getEchoDb(DB_MASTER);

        $row = $db->selectRow(INTERACTION_TABLE_NAME, '*', array('interaction_id' => $id), __METHOD__);

        if (!$row) {
            throw new MWException("No Interaction found with ID: $id");
        }

        return Interaction::newFromRow($row);
    }

}
