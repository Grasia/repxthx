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
        $continuation = "";

        $last = 0;
        $userArray = ReptxThxUser::getUsersChunk($last);

        while (!empty($userArray)) {
            foreach ($userArray as $user) {
                self::updateUserRepValue($user);
            }

            $userArray = ReptxThxUser::getUsersChunk($last);
        }
    }

    private function updateUserRepValue($user) {

        $revWeight = 1;
        $thankGivenWeight = 1;
        $thankReceivedWeight = 1;

        $tetaR = 1;
        $roF = 1;

        $repValue = 0;
        
        $fitnessAvg = ReptxThxArticle::getFitnessAvg();
        $userInteractionDegree = Interaction::getUserDegree();

        $createdArticles = Interaction::getUserCreatedArticles($user->getId());
        foreach ($createdArticles as $article) {
            $repValue += $revWeight*($article->getFitness() - $roF*$fitnessAvg);
        }

        $thanksRecArticles = Interaction::getUserThanksReceived($user->getId());
        foreach ($thanksRecArticles as $article) {
            $repValue += $thankGivenWeight*($article->getFitness() - $roF*$fitnessAvg);
        }

        $thanksGivArticles = Interaction::getUserThanksGiven($user->getId());
        foreach ($thanksGivArticles as $article) {
            $repValue += $thankReceivedWeight*($article->getFitness() - $roF*$fitnessAvg);
        }
        
        $repFinalVal = 1/pow($userInteractionDegree,$tetaR)*$repValue;
        
        $user->updateTempRepValue($repFinalVal);
    }

}
