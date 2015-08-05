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
    
    private function updateReputationValue(){
        $continuation = "";
        
        $userArray = ReptxThx_User::getAllUsers($continuation);
        
        foreach($userArray as $user){
            
        }
    }

}
