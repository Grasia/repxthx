<?php

class ReptxThxAlgorithm {

    /**
     * Executes the logic of the Reputation-credit fitness algorithm described 
     * in the article Network-Driven Reputation in Online Scientific Communities
     * by Hao Liao, Rui Xiao, Giulio Cimini, Matúš Medo.
     * 
     * for more info see http://journals.plos.org/plosone/article?id=10.1371/journal.pone.0112022
     */
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

    /**
     * copies the temporary reputation and credit values
     * to the corresponding db columns to make this values
     * final
     */
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

    /**
     * copies the temporary reputation and credit values to 
     * the corresponding db columns to make this values final
     */
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

    /**
     * calculates the fitness value for al the pages
     */
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

    /**
     * Normalizes the vector of fitness values so that 
     * fitness[0]^2 + fitness[1]^2 + ... + fitness[n]^2 = 1
     */
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

    /**
     * calculates the reputation and credit value for al the users
     */
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

    /**
     * Normalizes the vectors of reputation and credit values so that 
     * reputation[0]^2 + reputation[1]^2 + ... + reputation[n]^2 = 1
     * credit[0]^2 + credit[1]^2 + ... + credit[n]^2 = 1
     */
    private function normalizeRepCred() {
        $last = 0;
        $userArray = ReptxThxUser::getUsersChunk($last);

        $repNormVal = ReptxThxUser::getRepNormValue();
        $credNormVal = ReptxThxUser::getCredNormValue();
        while (!empty($userArray)) {

            foreach ($userArray as $user) {
                if ($repNormVal > 0) {
                    $user->normalizeReputation($repNormVal);
                }
                if ($credNormVal > 0) {
                    $user->normalizeCredit($credNormVal);
                    ;
                }
            }

            $userArray = ReptxThxUser::getUsersChunk($last);
        }
    }

    /**
     * calculates the fitness value for a given page
     */
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

    /**
     * calculates the reputation value for a given user
     */
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

    /**
     * calculates the credit value for a given user
     */
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
