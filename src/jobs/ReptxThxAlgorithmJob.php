<?php

class ReptxThxAlgorithmJob extends Job {

    public function __construct($title, $params) {
        parent::__construct('executeReptxThxAlgorithm', $title, $params);
    }

    public function run() {
        ReptxThxAlgorithm::execute();
        return true;
    }

}
