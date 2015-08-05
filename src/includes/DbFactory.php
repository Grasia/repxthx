<?php

/**
 * Database factory class, this will determine whether to use the main database
 * or an external database defined in configuration file
 */
class DbFactory {

    function getForRead() {
        return wfGetDB( DB_SLAVE );
    }
    
    function getForWrite() {
        return wfGetDB( DB_MASTER );
    }

}
