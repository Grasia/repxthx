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

    public static function updateReputationValue() {

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
    
    

    private function updateUserRepValue(ReptxThxUser $user) {
        error_log("updateUserRepValue");
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
