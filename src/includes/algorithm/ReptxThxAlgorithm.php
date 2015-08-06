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
        ReptxThx_User::insertNewUsers();
    }

    public static function updateReputationValue() {
        $continuation = "";

        $last = 0;
        $userArray = ReptxThx_User::getUsersChunk($last);

        while (!empty($userArray)) {
            foreach ($userArray as $user) {
                self::updateUserRepValue($user);
            }

            $userArray = ReptxThx_User::getUsersChunk($last);
        }
    }

    private function updateUserRepValue($user) {
        
        $createdArticles = Article::getUserCreatedArticles($user->getId);
    }

}
