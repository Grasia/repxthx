<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ReptxThxAlgorithm {

    public static function execute() {
        ReptxThxProperties::setInteractionCount(0);
        ReptxThxUser::insertNewUsers();
        ReptxThxPage::insertNewPages();
        Interaction::insertNewPageCreations();

        $Rn = 0;
        $An = 0;
        $Fn = 0;
        $Rn1 = 0;
        $An1 = 0;
        $Fn1 = 0;

        $count = 0;
        do {
            $Rn = ReptxThxUser::getReputationSum();
            $An = ReptxThxUser::getCreditSum();
            $Fn = ReptxThxPage::getFitnessSum();

            self::updateRepCredValue();
            self::updateFitnessValue();

            self::normalizeRepCred();
            self::normalizeFitness();

            $Rn1 = ReptxThxUser::getReputationSum();
            $An1 = ReptxThxUser::getCreditSum();
            $Fn1 = ReptxThxPage::getFitnessSum();

            error_log('index: ' . $count . ' condVal: ' . (abs($Rn1 - $Rn) + abs($An1 - $An) + abs($Fn1 - $Fn)));
            $count += 1;
        } while ((abs($Rn1 - $Rn) + abs($An1 - $An) + abs($Fn1 - $Fn)) > pow(10, -8));

        self::commitRepCredChanges();
        self::commitFitChanges();
    }

    private function commitRepCredChanges() {
        $last = 0;
        $userArray = ReptxThxUser::getUsersChunk($last);

        while (!empty($userArray)) {
            foreach ($userArray as $user) {
                $user->commitReputation();
                $user->commitCredit();
            }
            $userArray = ReptxThxUser::getUsersChunk($last);
        }
    }

    private function commitFitChanges() {
        $last = 0;
        $pagesArray = ReptxThxPage::getPagesChunk($last);

        while (!empty($pagesArray)) {
            foreach ($pagesArray as $page) {
                $page->commitFitness();
            }

            $pagesArray = ReptxThxPage::getPagesChunk($last);
        }
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

        $fitNormVal = ReptxThxPage::getFitNormValue();
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

        $fitnessAvg = ReptxThxPage::getFitnessAvg();
        $creditAvg = ReptxThxUser::getCreditAvg();
        while (!empty($userArray)) {
            foreach ($userArray as $user) {
                self::updateUserRepValue($user, $fitnessAvg);
                self::updateUserCredValue($user, $creditAvg);
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
                if($repNormVal > 0){
                    $user->normalizeReputation($repNormVal);
                }
                if($credNormVal > 0){
                    $user->normalizeCredit($credNormVal);;
                }
                
            }

            $userArray = ReptxThxUser::getUsersChunk($last);
        }
    }

    private function updatePageFitnessValue(ReptxThxPage $page) {
        global $giveThankWeight, $receiveThankWeight, $tetaF, $roR, $lambda, $phiP;

        $repTerm = 0;
        $credTerm = 0;
        $fitnesFinalVal = 0;

        $reputationAvg = ReptxThxUser::getReputationAvg();
        $pageThanksDegree = Interaction::getPageThanksDegree($page->getPageId());

        if ($pageThanksDegree > 0) {

            $thanksRecUsers = Interaction::getPageThanksReceived($page->getPageId());
            foreach ($thanksRecUsers as $user) {
                $repTerm += $receiveThankWeight * ($user->getTempReputationValue() - $roR * $reputationAvg);
            }

            $thanksGivUsers = Interaction::getPageThanksGiven($page->getPageId());
            foreach ($thanksGivUsers as $user) {
                $repTerm += $giveThankWeight * ($user->getTempReputationValue() - $roR * $reputationAvg);
            }

            $fitnesFinalVal = ((1 - $lambda) / pow($pageThanksDegree, $tetaF)) * $repTerm;
        }

        $pageCreationDegree = Interaction::getPageCretionDegree($page->getPageId());

        if ($pageCreationDegree > 0) {
            $creatingUsers = Interaction::getPageCreatingUsers($page->getPageId());
            foreach ($creatingUsers as $user) {
                $credTerm += ($user->getTempCreditValue());
            }

            $fitnesFinalVal += ($lambda / pow($pageCreationDegree, $phiP)) * $credTerm;
        }

        $page->updateTempFitnessValue($fitnesFinalVal);
    }

    private function updateUserRepValue(ReptxThxUser $user, $fitnessAvg) {
        global $giveThankWeight, $receiveThankWeight, $tetaR, $roF;

        $repValue = 0;
        $repFinalVal = 0;

        $userThanksDegree = Interaction::getUserThankDegree($user->getUserId());
        error_log('updateUserCredValue userId=' . $user->getUserId() . ' creationDegree=' . $userThanksDegree);
        if ($userThanksDegree > 0) {

            $thanksRecArticles = Interaction::getUserThanksReceived($user->getUserId());
            foreach ($thanksRecArticles as $article) {
                $repValue += $receiveThankWeight * ($article->getTempFitness() - $roF * $fitnessAvg);
            }

            $thanksGivArticles = Interaction::getUserThanksGiven($user->getUserId());
            foreach ($thanksGivArticles as $article) {
                $repValue += $giveThankWeight * ($article->getTempFitness() - $roF * $fitnessAvg);
            }

            $repFinalVal = (1 / pow($userThanksDegree, $tetaR)) * $repValue;
        }

        $user->updateTempRepValue($repFinalVal);
    }

    private function updateUserCredValue(ReptxThxUser $user, $creditAvg) {
        global $phiA, $roA;

        $credValue = 0;
        $credFinalVal = 0;

        $userCreationDegree = Interaction::getUserCreationDegree($user->getUserId());


        if ($userCreationDegree > 0) {
            $createdPages = Interaction::getUserCreatedArticles($user->getUserId());
            foreach ($createdPages as $page) {
                $credValue += ($page->getTempFitness() - $roA * $creditAvg);
            }

            $credFinalVal = (1 / pow($userCreationDegree, $phiA)) * $credValue;
        }

        $user->updateTempCredValue($credFinalVal);
    }

}
