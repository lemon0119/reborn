<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Exercise
{
    public static function getExerise($exerID, $type){
        switch ($type) {
        case "listen":
            return ListenType::model()->find('exerciseID=?',[$exerID]);
        case "look":
            return LookType::model()->find('exerciseID=?',[$exerID]);
        case "key":
            return KeyType::model()->find('exerciseID=?',[$exerID]);
        default:
            return NULL;
        }
    }
}
