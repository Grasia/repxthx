<?php

class ReptxThxAlgorithmJob extends Job {

    public function __construct($title, $params) {
        // Replace synchroniseThreadArticleData with an identifier for your job.
        parent::__construct('executeReptxThxAlgorithm', $title, $params);
    }

    /**
     * Execute the job
     *
     * @return bool
     */
    public function run() {
        ReptxThxAlgorithm::execute();

//        $jobParams = array();
//        $title = Title::newMainPage();
//
//        $job = new ReptxThxAlgorithmJob($title, $jobParams);
//
//        JobQueueGroup::singleton()->push($job);
        return true;
    }

}
