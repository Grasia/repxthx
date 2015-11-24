<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class AbstractMapper {

    protected $dbFactory;

    public function __construct(DbFactory $dbFactory = null) {
        if ($dbFactory === null) {
            $dbFactory = new DbFactory();
        }
        $this->dbFactory = $dbFactory;
    }

}
