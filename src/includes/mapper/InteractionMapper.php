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
        error_log("interaction insert ");
        $dbw = $this->dbFactory->getForWrite();

        $id = $dbw->nextSequenceValue('reptxThx_interaction_id');
        error_log($id);
        if ($id) {
            $row['interaction_id'] = $id;
        }

        $row = $interaction->toDbArray();
        error_log((string) implode(" | ", $row));
        error_log("row todbarray ");
        $res = $dbw->insert('reptxThx_interaction', $row, __METHOD__);
        error_log("inserted " . $res);
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
    public function getById($id) {
        $db = $this->dbFactory->getEchoDb(DB_MASTER);

        $row = $db->selectRow(INTERACTION_TABLE_NAME, '*', array('interaction_id' => $id), __METHOD__);

        if (!$row) {
            throw new MWException("No Interaction found with ID: $id");
        }

        return Interaction::newFromRow($row);
    }

}
