<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class AbstractMapper {

    /**
     * Echo database factory
     * @var MWEchoDbFactory
     */
    protected $dbFactory;

    /**
     * @param MWEchoDbFactory|null
     */
    public function __construct(DbFactory $dbFactory = null) {
        if ($dbFactory === null) {
            $dbFactory = new DbFactory();
        }
        $this->dbFactory = $dbFactory;
    }

}
