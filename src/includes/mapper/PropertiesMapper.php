<?php

class PropertiesMapper extends AbstractMapper {

    public function setProperty($key, $value) {
        $db = $this->dbFactory->getForWrite();

        $db->update('reptxthx_properties', array('reptxthx_prop_val' => $value), array('reptxthx_prop_key' => $key), __METHOD__);
    }

    public function getProperty($key) {
        $db = $this->dbFactory->getForRead();

        $res = $db->selectRow('reptxthx_properties', array('value' => 'reptxthx_prop_val'), array('reptxthx_prop_key' => $key), __METHOD__);

        if ($res != false) {
            return $res->value;
        }

        return '';
    }

    public function insertProperty($key, $value) {
        $db = $this->dbFactory->getForWrite();

        $db->insert('reptxthx_properties', array('reptxthx_prop_key' => $key, 'reptxthx_prop_val' => $value), __METHOD__);
    }

}
