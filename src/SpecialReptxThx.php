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
        $row =(object) array(
            "reptxthx_user_id" => "a",
            "user_id" => "b",
            "user_rep_value" => "c",
            "user_cred_value" => "d",
            "user_last_rep_timestamp" => "e",
            "user_last_cred_timestamp" => "f"
        );
//        echo($row);
        $user = ReptxThx_User::newFromRow($row);
        $out = $this->getOutput();
        $out->addWikiMsg( (string)$user);
        
        ReptxThx_User::insertAllUsers();
    }

    protected function getGroupName() {
        return 'other';
    }

}
