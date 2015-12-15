<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ReptxThxHooks {

    const THANKS_EVENT_TYPE_ID = 1;
    const REV_EVENT_TYPE_ID = 2;
    const THANKS_EVENT_ECHO_TYPE = "edit-thank";

    /**
     * @param $updater DatabaseUpdater object
     * @return bool true in all cases
     */
    public static function initExtension() {


    }

    public static function onLoadExtensionSchemaUpdates($updater) {
        $dir = __DIR__;
        $baseSQLFile = "$dir/ReptxThx.sql";

        $updater->addExtensionTable('reptxthx_interaction', $baseSQLFile);
        $updater->addExtensionTable('reptxthx_user', $baseSQLFile);
        $updater->addExtensionTable('reptxthx_page', $baseSQLFile);
        $updater->addExtensionTable('reptxthx_properties', $baseSQLFile);

        self::setInitialProperties();
        return true;
    }

    public static function onEchoEventInsertComplete($event) {

        $event_type = $event->getType();

        if ($event_type == self::THANKS_EVENT_ECHO_TYPE) {
            $eventThankedUser = $event->getExtraParam("thanked-user-id");
            $eventTitle = $event->getTitle()->getArticleId();
            $eventThankingUser = $event->getAgent()->getId();

            $obj = Interaction::create(self::THANKS_EVENT_TYPE_ID, $eventThankingUser, $eventThankedUser, $eventTitle);
        }
    }

    public static function onPageContentSaveComplete($article, $user, $content, $summary, $isMinor, $isWatch, $section, $flags, $revision, $status, $baseRevId) {

        try {
            Interaction::create(self::REV_EVENT_TYPE_ID, $user->getId(), NULL, $article->getId());
        } catch (Exception $ex) {
            error_log("Error createing revision interaction");
        }
    }

    private function setInitialProperties() {

        $interactionCount = ReptxThxProperties::getInteractionCount();
        if ($interactionCount == '') {
            ReptxThxProperties::insertInteractionCount(0);
        }

        $algoTimestamp = ReptxThxProperties::getLastAlgorithmTimestamp();
        if ($algoTimestamp == '') {
            ReptxThxProperties::insertLastAlgorithmTimestamp(0);
        }
    }

    function onParserSetup(&$parser) {

        $parser->setFunctionHook('rept', 'ReptxThxHooks::renderRept');
        $parser->setFunctionHook('cred', 'ReptxThxHooks::renderCred');
    }

    function renderRept($parser, $userName = '') {

        $parser->disableCache();
        $userId = User::idFromName($userName);
        if (isset($userName) && $userId !== null) {
            
            $user = ReptxThxUser::newFromId($userId);
            $rept = $user !== null ? $user->getReputationValue() : null;

            $output = $rept === null ? "" : "$userName's reputation value is $rept";
        } else {
            $output = "";
        }

        return $output;
    }
    
    function renderCred($parser, $userName = '') {

        $parser->disableCache();
        error_log($userName);
        $userId = User::idFromName($userName);
        error_log($userId);
        if (isset($userName) && $userId !== null) {
            
            $user = ReptxThxUser::newFromId($userId);
            error_log($user);
            $cred = $user !== null ? $user->getCreditValue() : null;

            $output = $cred === null ? "" : "$userName's credit value is $cred";
        } else {
            $output = "";
        }

        return $output;
    }


}
