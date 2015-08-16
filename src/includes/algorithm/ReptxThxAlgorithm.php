<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ReptxThxAlgorithm {

    private function stallCondition() {
        return true;
    }

    private function insertNewUsers() {
        ReptxThxUser::insertNewUsers();
    }

    private function insertNewPages() {
        ReptxThxPage::insertNewPages();
    }

    public static function execute() {
        self::updateRepCredValue();
        self::updateFitnessValue();

        self::normalizeRepCred();
        self::normalizeFitness();
    }

    private function updateFitnessValue() {
        $last = 0;
        $pagesArray = ReptxThxPage::getPagesChunk($last);

        while (!empty($pagesArray)) {
            foreach ($pagesArray as $page) {
                self::updatePageFitnessValue($page);
            }

            $pagesArray = ReptxThxPage::getPagesChunk($last);
        }
    }

    private function normalizeFitness() {
        $last = 0;
        $pagesArray = ReptxThxPage::getPagesChunk($last);
        
        $fitNormVal = ReptxThxUser::getRepNormValue();
        while (!empty($pagesArray)) {
            foreach ($pagesArray as $page) {
                $page->normalizeFitness($fitNormVal);
            }

            $pagesArray = ReptxThxPage::getPagesChunk($last);
        }
    }

    private function updateRepCredValue() {
        $last = 0;
        $userArray = ReptxThxUser::getUsersChunk($last);

        while (!empty($userArray)) {
            foreach ($userArray as $user) {
                self::updateUserRepValue($user);
                self::updateUserCredValue($user);
            }

            $userArray = ReptxThxUser::getUsersChunk($last);
        }
    }

    private function normalizeRepCred() {
        $last = 0;
        $userArray = ReptxThxUser::getUsersChunk($last);

        $repNormVal = ReptxThxUser::getRepNormValue();
        $credNormVal = ReptxThxUser::getCredNormValue();
        while (!empty($userArray)) {
            foreach ($userArray as $user) {
                $user->normalizeReputation($repNormVal);
                $user->normalizeCredit($credNormVal);
            }

            $userArray = ReptxThxUser::getUsersChunk($last);
        }
    }

    private function updatePageFitnessValue(ReptxThxPage $page) {
        $revWeight = 1;
        $thankGivenWeight = 1;
        $thankReceivedWeight = 1;

        $tetaF = 1;
        $roR = 0.1;

        $fitnessVal = 0;
        $fitnesFinalVal = 0;

        $reputationAvg = ReptxThxUser::getReputationAvg();
        $pageInteractionDegree = Interaction::getPageCompleteDegree($page->getPageId());

        if ($pageInteractionDegree > 0) {
            $creatingUsers = Interaction::getPageCreatingUsers($page->getPageId());
            foreach ($creatingUsers as $user) {
                $fitnessVal += $revWeight * ($user->getTempReputationValue() - $roR * $reputationAvg);
            }

            $thanksRecUsers = Interaction::getPageThanksReceived($page->getPageId());
            foreach ($thanksRecUsers as $user) {
                $fitnessVal += $thankReceivedWeight * ($user->getTempReputationValue() - $roR * $reputationAvg);
            }

            $thanksGivUsers = Interaction::getPageThanksGiven($page->getPageId());
            foreach ($thanksGivUsers as $user) {
                $fitnessVal += $thankGivenWeight * ($user->getTempReputationValue() - $roR * $reputationAvg);
            }

            $fitnesFinalVal = 1 / pow($pageInteractionDegree, $tetaF) * $fitnessVal;
        }

        error_log($page->getPageId() . " " . $fitnesFinalVal);

        $page->updateTempFitnessValue($fitnesFinalVal);
    }

    private function updateUserRepValue(ReptxThxUser $user) {
        $revWeight = 1;
        $thankGivenWeight = 1;
        $thankReceivedWeight = 1;

        $tetaR = 1;
        $roF = 0.1;

        $repValue = 0;
        $repFinalVal = 0;

        $fitnessAvg = ReptxThxPage::getFitnessAvg();
        $userInteractionDegree = Interaction::getUserCompleteDegree($user->getUserId());

        if ($userInteractionDegree > 0) {
            $createdArticles = Interaction::getUserCreatedArticles($user->getUserId());
            foreach ($createdArticles as $article) {
                $repValue += $revWeight * ($article->getFitness() - $roF * $fitnessAvg);
            }

            $thanksRecArticles = Interaction::getUserThanksReceived($user->getUserId());
            foreach ($thanksRecArticles as $article) {
                $repValue += $thankReceivedWeight * ($article->getFitness() - $roF * $fitnessAvg);
            }

            $thanksGivArticles = Interaction::getUserThanksGiven($user->getUserId());
            foreach ($thanksGivArticles as $article) {
                $repValue += $thankGivenWeight * ($article->getFitness() - $roF * $fitnessAvg);
            }

            $repFinalVal = 1 / pow($userInteractionDegree, $tetaR) * $repValue;
        }

        $user->updateTempRepValue($repFinalVal);
    }

    private function updateUserCredValue(ReptxThxUser $user) {
        error_log("updateUserCredValue");

        $tetaA = 1;
        $roA = 0.1;

        $credValue = 0;
        $credFinalVal = 0;

        $creditAvg = ReptxThxUser::getCreditAvg();
        $userCreationDegree = Interaction::getUserCreationDegree($user->getUserId());

        if ($userCreationDegree > 0) {
            $createdArticles = Interaction::getUserCreatedArticles($user->getUserId());
            foreach ($createdArticles as $article) {
                $credValue += ($article->getFitness() - $roA * $creditAvg);
            }

            $credFinalVal = 1 / pow($userCreationDegree, $tetaA) * $credValue;
        }

        $user->updateTempCredValue($credFinalVal);
    }

}
