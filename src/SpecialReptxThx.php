<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SpecialReptxThx extends SpecialPage {

    /**
     * Initialize the special page.
     */
    public function __construct() {
        // A special page should at least have a name.
        // We do this by calling the parent class (the SpecialPage class)
        // constructor method with the name as first and only parameter.
        parent::__construct('ReptxThx');
    }

    /**
     * Shows the page to the user.
     * @param string $sub: The subpage string argument (if any).
     *  [[Special:HelloWorld/subpage]].
     */
    public function execute($sub) {

        $out = $this->getOutput();

        $out->setPageTitle($this->msg('example-helloworld'));

        // Parses message from .i18n.php as wikitext and adds it to the
        // page output.
//        ReptxThxPage::insertNewPages();
        ReptxThxUser::insertNewUsers();
//        $arr = Interaction::getUserThanksGiven(1);
//        getUserThanksReceived($userId)
//        print_r($arr);
        
        ReptxThxAlgorithm::updateReputationValue();
//        $out->addWikiMsg((string) $i);
    }

    protected function getGroupName() {
        return 'other';
    }

}
