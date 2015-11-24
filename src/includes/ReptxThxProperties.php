<?php

class ReptxThxProperties {

    public static function setInteractionCount($value) {
        $propMapper = new PropertiesMapper();
        $propMapper->setProperty('interaction_count', $value);
    }

    public static function getInteractionCount() {
        $propMapper = new PropertiesMapper();
        $value = $propMapper->getProperty('interaction_count');

        return $value;
    }
    
    public static function insertInteractionCount($value) {
        $propMapper = new PropertiesMapper();
        $propMapper->insertProperty('interaction_count', $value);
    }

    public static function setLastAlgorithmTimestamp($value) {
        $propMapper = new PropertiesMapper();
        $propMapper->setProperty('last_timestamp', $value);
    }

    public static function getLastAlgorithmTimestamp() {
        $propMapper = new PropertiesMapper();
        $value = $propMapper->getProperty('last_timestamp');

        return $value;
    }
    
    public static function insertLastAlgorithmTimestamp($value) {
        $propMapper = new PropertiesMapper();
        $propMapper->insertProperty('last_timestamp', $value);
    }

}
